
<?php
require_once __DIR__ . '/auth.php';

$types = ['Pemasukan', 'Pengeluaran'];
$categories = [
    'Penjualan Produk',
    'Pembelian Bahan Baku',
    'Produksi',
    'Kemasan',
    'Transportasi',
    'Operasional',
    'Gaji/Upah',
    'Lainnya'
];

$error = '';

// Validasi bulan yang dipilih.
$month = $_GET['month'] ?? date('Y-m');

if (
    !preg_match('/^\d{4}-\d{2}$/', $month) ||
    !checkdate(
        (int) substr($month, 5, 2),
        1,
        (int) substr($month, 0, 4)
    )
) {
    $month = date('Y-m');
}

// Proses tambah dan hapus transaksi.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add') {
            $type = $_POST['type'] ?? '';
            $category = $_POST['category'] ?? '';
            $amountRaw = $_POST['amount'] ?? '';
            $transactionDate = $_POST['transaction_date'] ?? '';
            $description = trim($_POST['description'] ?? '');

            $dateValid = is_string($transactionDate)
                && preg_match('/^\d{4}-\d{2}-\d{2}$/', $transactionDate)
                && checkdate(
                    (int) substr($transactionDate, 5, 2),
                    (int) substr($transactionDate, 8, 2),
                    (int) substr($transactionDate, 0, 4)
                );

            if (
                !in_array($type, $types, true) ||
                !in_array($category, $categories, true) ||
                !is_numeric($amountRaw) ||
                (float) $amountRaw <= 0 ||
                !$dateValid
            ) {
                throw new Exception(
                    'Data transaksi tidak valid. Periksa kembali isian formulir.'
                );
            }

            $amount = (float) $amountRaw;

            $stmt = $pdo->prepare("
                INSERT INTO finance_transactions
                    (type, category, amount, transaction_date, description)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $type,
                $category,
                $amount,
                $transactionDate,
                $description
            ]);

            $savedMonth = substr($transactionDate, 0, 7);

            header(
                'Location: finance.php?saved=1&month=' .
                urlencode($savedMonth)
            );
            exit;

        } elseif ($action === 'delete') {
            $id = filter_input(
                INPUT_POST,
                'id',
                FILTER_VALIDATE_INT
            );

            if (!$id || $id < 1) {
                throw new Exception('ID transaksi tidak valid.');
            }

            $stmt = $pdo->prepare("
                DELETE FROM finance_transactions
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            $returnMonth = $_POST['return_month'] ?? date('Y-m');

            if (
                !preg_match('/^\d{4}-\d{2}$/', $returnMonth) ||
                !checkdate(
                    (int) substr($returnMonth, 5, 2),
                    1,
                    (int) substr($returnMonth, 0, 4)
                )
            ) {
                $returnMonth = date('Y-m');
            }

            header(
                'Location: finance.php?deleted=1&month=' .
                urlencode($returnMonth)
            );
            exit;
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Ringkasan transaksi buku kas untuk bulan terpilih.
$summaryStmt = $pdo->prepare("
    SELECT
        COALESCE(SUM(
            CASE WHEN type = 'Pengeluaran' THEN amount ELSE 0 END
        ), 0) AS expense
    FROM finance_transactions
    WHERE DATE_FORMAT(transaction_date, '%Y-%m') = ?
");

$summaryStmt->execute([$month]);
$summary = $summaryStmt->fetch();

// Hitung omzet dan modal produk dari pesanan selesai pada bulan terpilih.
$orderStmt = $pdo->prepare("
    SELECT COALESCE(SUM(o.total_price), 0) AS omzet
    FROM orders o
    WHERE o.status = 'Selesai'
      AND DATE_FORMAT(o.created_at, '%Y-%m') = ?
");

$orderStmt->execute([$month]);
$orderSummary = $orderStmt->fetch();

// Simpan nilai hasil query.
$omzet = (float) ($orderSummary['omzet'] ?? 0);
$pengeluaranLain = (float) ($summary['expense'] ?? 0);

// Sesuai kebutuhan aplikasi: keuntungan = omzet - pengeluaran kas yang dicatat manual.
$totalPengeluaran = $pengeluaranLain;
$keuntunganBersih = $omzet - $totalPengeluaran;

$income = $omzet;
$expense = $totalPengeluaran;
$balance = $keuntunganBersih;

// Daftar transaksi berdasarkan bulan terpilih.
$rowsStmt = $pdo->prepare("
    SELECT *
    FROM finance_transactions
    WHERE DATE_FORMAT(transaction_date, '%Y-%m') = ?
    ORDER BY transaction_date DESC, id DESC
");

$rowsStmt->execute([$month]);
$transactions = $rowsStmt->fetchAll();

admin_header('Monitoring Keuangan & Pembukuan', 'finance');
?>

<div class="page-heading">
    <div>
        <p class="eyebrow">KEUANGAN MITRA</p>
        <h1>Monitoring Keuangan &amp; Pembukuan Digital</h1>
        <p class="muted">
            Catat pemasukan dan pengeluaran usaha UD. Tahu Yaya secara teratur.
        </p>
    </div>
</div>

<?php if (isset($_GET['saved'])): ?>
    <div class="alert success">Transaksi berhasil dicatat.</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert success">Transaksi berhasil dihapus.</div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert danger"><?= e($error) ?></div>
<?php endif; ?>




<section class="finance-cards">
    <article class="finance-card income-card">
        <span class="finance-card-icon">↗</span>
        <div>
            <p>Pemasukan (Omzet)</p>
            <strong><?= rupiah($omzet) ?></strong>
            <small>Total pesanan selesai pada bulan terpilih</small>
        </div>
    </article>

    <article class="finance-card expense-card">
        <span class="finance-card-icon">↘</span>
        <div>
            <p>Pengeluaran Kas</p>
            <strong><?= rupiah($totalPengeluaran) ?></strong>
            <small>Pengeluaran yang benar-benar dicatat di buku kas</small>
        </div>
    </article>

    <article class="finance-card balance-card">
        <span class="finance-card-icon">＝</span>
        <div>
            <p>Keuntungan Bersih</p>
            <strong><?= rupiah($keuntunganBersih) ?></strong>
            <small>Omzet dikurangi pengeluaran kas yang dicatat</small>
        </div>
    </article>
</section>


<section class="panel">
    <div class="panel-head">
        <div>
            <h2>Tambah Catatan Transaksi</h2>
            <p class="muted">
                Masukkan transaksi pemasukan atau pengeluaran yang benar-benar terjadi.
            </p>
        </div>
    </div>

    <form method="post" class="finance-form">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add">

        <div class="field">
            <label for="type">Jenis Transaksi *</label>
            <select id="type" name="type" required>
                <option value="">Pilih jenis</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= e($type) ?>">
                        <?= e($type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="category">Kategori *</label>
            <select id="category" name="category" required>
                <option value="">Pilih kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= e($category) ?>">
                        <?= e($category) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="amount">Nominal (Rp) *</label>
            <input
                id="amount"
                name="amount"
                type="number"
                min="1"
                step="1"
                required
                placeholder="Contoh: 150000"
            >
        </div>

        <div class="field">
            <label for="transaction_date">Tanggal Transaksi *</label>
            <input
                id="transaction_date"
                name="transaction_date"
                type="date"
                required
                value="<?= e(date('Y-m-d')) ?>"
            >
        </div>

        <div class="field finance-description">
            <label for="description">Keterangan</label>
            <input
                id="description"
                name="description"
                maxlength="255"
                placeholder="Contoh: pembelian kemasan produk"
            >
        </div>

        <div class="finance-submit">
            <button class="btn" type="submit">
                ＋ Simpan Transaksi
            </button>
        </div>
    </form>
</section>

<section class="panel">
    <div class="panel-head finance-table-head">
        <div>
            <h2>Buku Kas Digital</h2>
            <p class="muted">Daftar transaksi pada bulan yang dipilih.</p>
        </div>

        <form method="get" class="search-form">
            <input
                type="month"
                name="month"
                value="<?= e($month) ?>"
                aria-label="Pilih bulan"
            >
            <button class="btn btn-light" type="submit">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Dicatat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!$transactions): ?>
                    <tr>
                        <td colspan="7" class="empty">
                            Belum ada transaksi pada periode ini.
                            Tambahkan catatan pada formulir di atas.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td>
                            <?= e(date(
                                'd/m/Y',
                                strtotime($t['transaction_date'])
                            )) ?>
                        </td>

                        <td>
                            <span class="status <?= $t['type'] === 'Pemasukan' ? 'selesai' : 'dibatalkan' ?>">
                                <?= e($t['type']) ?>
                            </span>
                        </td>

                        <td><?= e($t['category']) ?></td>
                        <td><?= e($t['description'] ?: '-') ?></td>

                        <td>
                            <b class="<?= $t['type'] === 'Pemasukan' ? 'money-in' : 'money-out' ?>">
                                <?= $t['type'] === 'Pemasukan' ? '+' : '−' ?>
                                <?= rupiah($t['amount']) ?>
                            </b>
                        </td>

                        <td>
                            <?= e(date(
                                'd/m/Y H:i',
                                strtotime($t['created_at'])
                            )) ?>
                        </td>

                        <td>
                            <form
                                method="post"
                                onsubmit="return confirm('Hapus catatan transaksi ini?')"
                            >
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $t['id'] ?>"
                                >
                                <input
                                    type="hidden"
                                    name="return_month"
                                    value="<?= e($month) ?>"
                                >
                                <button
                                    class="btn btn-small btn-danger"
                                    type="submit"
                                >
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="finance-footer">
    <span>
        Jumlah pemasukan:
        <b class="money-in"><?= rupiah($omzet) ?></b>
    </span>

    <span>
        Jumlah pengeluaran kas:
        <b class="money-out"><?= rupiah($totalPengeluaran) ?></b>
    </span>

    <span>
        Keuntungan bersih:
        <b><?= rupiah($keuntunganBersih) ?></b>
    </span>
</div>
</section>

<?php admin_footer(); ?>
