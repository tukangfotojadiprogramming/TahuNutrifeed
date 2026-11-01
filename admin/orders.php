<?php
require_once __DIR__ . '/auth.php';

$allowedStatuses = ['Baru', 'Diproses', 'Selesai', 'Dibatalkan'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? '';

    if (!$id || !in_array($status, $allowedStatuses, true)) {
        $error = 'Data status pesanan tidak valid.';
    } else {
        try {
            $pdo->beginTransaction();

            // Lock pesanan agar perubahan status dan pembukuan tetap konsisten.
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? FOR UPDATE");
            $stmt->execute([$id]);
            $order = $stmt->fetch();

            if (!$order) {
                throw new RuntimeException('Pesanan tidak ditemukan.');
            }

            $oldStatus = $order['status'];
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            if ($status === 'Selesai') {
                // Satu pesanan hanya memiliki satu catatan pemasukan otomatis.
                $description = 'Pesanan #TNF-' . (int)$order['id'] .
                    ' - ' . $order['customer_name'] . ' - ' . $order['product_name'];

                $check = $pdo->prepare("SELECT id FROM finance_transactions WHERE order_id = ? LIMIT 1");
                $check->execute([$id]);
                $financeId = $check->fetchColumn();

                if ($financeId) {
                    $updateFinance = $pdo->prepare("UPDATE finance_transactions
                        SET type = 'Pemasukan', category = 'Penjualan Produk',
                            amount = ?, transaction_date = CURDATE(), description = ?
                        WHERE order_id = ?");
                    $updateFinance->execute([(float)$order['total_price'], $description, $id]);
                } else {
                    $insertFinance = $pdo->prepare("INSERT INTO finance_transactions
                        (type, category, amount, transaction_date, description, created_by, order_id)
                        VALUES ('Pemasukan', 'Penjualan Produk', ?, CURDATE(), ?, ?, ?)");
                    $insertFinance->execute([
                        (float)$order['total_price'],
                        $description,
                        (int)($_SESSION['admin_id'] ?? 0),
                        $id
                    ]);
                }
            } elseif ($oldStatus === 'Selesai') {
                // Jika status dibatalkan/diubah dari Selesai, hapus catatan otomatis pesanan itu.
                $deleteFinance = $pdo->prepare("DELETE FROM finance_transactions WHERE order_id = ?");
                $deleteFinance->execute([$id]);
            }

            $pdo->commit();
            header('Location: orders.php?updated=1');
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('TahuNutrifeed order finance sync: ' . $e->getMessage());
            $error = 'Status belum berhasil disimpan. Pastikan migrasi SQL pembukuan otomatis sudah dijalankan. Detail: ' . $e->getMessage();
        }
    }
}

$search = trim($_GET['q'] ?? '');
$selected = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_name LIKE ? OR phone LIKE ? OR product_name LIKE ? ORDER BY created_at DESC, id DESC");
    $like = "%$search%";
    $stmt->execute([$like, $like, $like]);
    $orders = $stmt->fetchAll();
} else {
    $orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC, id DESC")->fetchAll();
}
// Ringkasan penjualan dari pesanan yang sudah selesai
$profitStmt = $pdo->query("
    SELECT
        COALESCE(SUM(o.total_price), 0) AS omzet,
        COALESCE(SUM(o.quantity * p.cost_per_pack), 0) AS total_modal
    FROM orders o
    LEFT JOIN products p ON p.id = o.product_id
    WHERE o.status = 'Selesai'
");

$profitSummary = $profitStmt->fetch();

$omzet = (float)($profitSummary['omzet'] ?? 0);
$totalModalTerjual = (float)($profitSummary['total_modal'] ?? 0);
$keuntunganKotor = $omzet - $totalModalTerjual;
$detail = null;
if ($selected) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$selected]);
    $detail = $stmt->fetch();
}
admin_header('Kelola Pesanan', 'orders');
?>
<div class="page-heading"><div><p class="eyebrow">TRANSAKSI USER</p><h1>Kelola Pesanan</h1><p class="muted">Pesanan berstatus Selesai otomatis dicatat sebagai pemasukan pembukuan.</p></div></div>
<?php if (isset($_GET['updated'])): ?><div class="alert success">Status pesanan berhasil diperbarui. Jika status Selesai, pemasukan otomatis disinkronkan ke pembukuan.</div><?php endif; ?>
<?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
<section class="finance-cards">
    <article class="finance-card income-card">
        <div>
            <p>Total Omzet</p>
            <strong><?= rupiah($omzet) ?></strong>
            <small>Dari pesanan selesai</small>
        </div>
    </article>

    <article class="finance-card expense-card">
        <div>
            <p>Modal Produk Terjual</p>
            <strong><?= rupiah($totalModalTerjual) ?></strong>
            <small>Modal berdasarkan jumlah terjual</small>
        </div>
    </article>

    <article class="finance-card balance-card">
        <div>
            <p>Keuntungan</p>
            <strong><?= rupiah($keuntunganKotor) ?></strong>
            <small>Omzet dikurangi modal produk terjual</small>
        </div>
    </article>
</section>
<?php if ($detail): ?>
<section class="panel order-detail">
  <div class="panel-head"><div><h2>Detail Pesanan #TNF-<?= (int)$detail['id'] ?></h2><p class="muted">Dibuat pada <?= e(date('d M Y, H:i', strtotime($detail['created_at']))) ?></p></div><a class="btn btn-light" href="orders.php">Tutup Detail</a></div>
  <div class="detail-grid-admin">
    <div><span class="detail-label">Nama Pemesan</span><b><?= e($detail['customer_name']) ?></b></div>
    <div><span class="detail-label">Nomor WhatsApp</span><b><?= e($detail['phone']) ?></b></div>
    <div><span class="detail-label">Produk</span><b><?= e($detail['product_name']) ?></b></div>
    <div><span class="detail-label">Jumlah</span><b><?= (int)$detail['quantity'] ?> kg</b></div>
    <div><span class="detail-label">Perkiraan Total</span><b><?= rupiah($detail['total_price']) ?></b></div>
    <div><span class="detail-label">Alamat</span><b><?= e($detail['address'] ?: 'Tidak diisi') ?></b></div>
    <div class="wide-detail"><span class="detail-label">Catatan</span><p><?= nl2br(e($detail['notes'] ?: 'Tidak ada catatan.')) ?></p></div>
  </div>
  <form method="post" class="status-form"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$detail['id'] ?>"><label for="status">Status pesanan</label><select name="status" id="status"><?php foreach ($allowedStatuses as $s): ?><option value="<?= e($s) ?>" <?= $detail['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="btn" type="submit">Simpan Status</button>
  <?php $phoneDigits = preg_replace('/\D+/', '', $detail['phone']); ?>
  <a class="btn btn-light" target="_blank" rel="noopener" href="https://wa.me/<?= e($phoneDigits) ?>">Hubungi Pemesan ↗</a></form>
</section>
<?php endif; ?>

<section class="panel">
  <div class="panel-head"><div><h2>Daftar Pesanan</h2><p class="muted"><?= count($orders) ?> pesanan tercatat</p></div>
  <form class="search-form" method="get"><input name="q" value="<?= e($search) ?>" placeholder="Cari nama, nomor, produk..."><button class="btn btn-light" type="submit">Cari</button></form></div>
  <div class="table-wrap"><table><thead><tr><th>No. Pesanan</th><th>Pemesan</th><th>Produk</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
  <?php if (!$orders): ?><tr><td colspan="7" class="empty">Belum ada pesanan yang cocok.</td></tr><?php endif; ?>
  <?php foreach ($orders as $o): ?><tr>
    <td><b>#TNF-<?= (int)$o['id'] ?></b><small class="subline"><?= e(date('d/m/Y H:i', strtotime($o['created_at']))) ?></small></td>
    <td><?= e($o['customer_name']) ?><small class="subline"><?= e($o['phone']) ?></small></td>
    <td><?= e($o['product_name']) ?></td><td><?= (int)$o['quantity'] ?> kg</td><td><?= rupiah($o['total_price']) ?></td>
    <td><span class="status <?= e(strtolower($o['status'])) ?>"><?= e($o['status']) ?></span></td>
    <td><a class="table-action" href="orders.php?id=<?= (int)$o['id'] ?>">Lihat / Ubah</a></td>
  </tr><?php endforeach; ?>
  </tbody></table></div>
</section>
<?php admin_footer(); ?>
