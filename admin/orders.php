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
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        header('Location: orders.php?updated=1');
        exit;
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
$detail = null;
if ($selected) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$selected]);
    $detail = $stmt->fetch();
}
admin_header('Kelola Pesanan', 'orders');
?>
<div class="page-heading"><div><p class="eyebrow">TRANSAKSI USER</p><h1>Kelola Pesanan</h1><p class="muted">Periksa informasi pelanggan dan perbarui status pesanan.</p></div></div>
<?php if (isset($_GET['updated'])): ?><div class="alert success">Status pesanan berhasil diperbarui.</div><?php endif; ?>
<?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

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
