
<?php
require 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id ?: 0]);

$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit('Produk tidak ditemukan. <a href="produk.php">Kembali</a>');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $qty = (int) ($_POST['quantity'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if (!$name || !$phone || !$address || $qty < 1) {
        $error = 'Nama lengkap, nomor WhatsApp, alamat lengkap, dan jumlah pesanan wajib diisi.';
    } elseif (!preg_match('/^[0-9]{1,13}$/', $phone)) {
        $error = 'Nomor WhatsApp harus berupa angka saja dan maksimal 13 digit.';
    } elseif (!preg_match('/\b(?:no\.?\s*\d+|nomor\s+\d+|#\s*\d+|\d+[a-zA-Z]?(?:\/\d+)?)\b/i', $address)) {
        $error = 'Alamat harus mencantumkan nomor rumah, contohnya: Jalan Mawar No. 12, Mataram.';
    } elseif ($qty > (int) $product['stock']) {
        $error = 'Jumlah melebihi stok yang tercatat. Hubungi admin untuk konfirmasi.';

    } else {
        try {
            $pdo->beginTransaction();

            // Kurangi stok jika jumlahnya masih mencukupi.
            $stockStmt = $pdo->prepare("
                UPDATE products
                SET stock = stock - ?
                WHERE id = ? AND stock >= ?
            ");

            $stockStmt->execute([
                $qty,
                $product['id'],
                $qty
            ]);

            if ($stockStmt->rowCount() !== 1) {
                throw new RuntimeException(
                    'Stok tidak mencukupi. Silakan muat ulang halaman.'
                );
            }

            $total = $qty * (float) $product['price'];

            // Simpan pesanan.
            $stmt = $pdo->prepare("
                INSERT INTO orders (
                    customer_name,
                    phone,
                    address,
                    product_id,
                    product_name,
                    quantity,
                    total_price,
                    status,
                    notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Baru', ?)
            ");

            $stmt->execute([
                $name,
                $phone,
                $address,
                $product['id'],
                $product['name'],
                $qty,
                $total,
                $notes
            ]);

            $orderId = $pdo->lastInsertId();

            // Simpan perubahan stok dan pesanan bersama-sama.
            $pdo->commit();

            $wa = '6287765470125';

            $msg = "Halo TahuNutrifeed, saya sudah mengirim pesanan melalui website.\n"
                . "Nomor pesanan: #$orderId\n"
                . "Nama: $name\n"
                . "Nomor WhatsApp: $phone\n"
                . "Alamat: $address\n"
                . "Produk: {$product['name']}\n"
                . "Jumlah: $qty kg\n"
                . "Perkiraan total: " . rupiah($total) . "\n"
                . "Mohon konfirmasi stok dan harga.";

            header(
                'Location: https://wa.me/' . $wa .
                '?text=' . rawurlencode($msg)
            );
            exit;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log('Gagal menyimpan pesanan: ' . $e->getMessage());

            $error = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Pesanan gagal disimpan. Silakan coba kembali.';
        }
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pesan Produk | TahuNutrifeed</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <!-- HEADER / NAVIGASI -->
    <header class="nav">

        <a class="brand" href="index.php">
             TahuNutrifeed
        </a>

        <nav>
            <a href="index.php">Beranda</a>
            <a href="produk.php">Produk</a>
            <a href="tentang.php">Tentang Kami</a>
            <a href="dampak.php">Dampak</a>
        </nav>

    </header>

    <!-- FORM PEMESANAN -->
    <main class="section form-section">

        <a href="detail.php?id=<?=(int) $product['id']?>">
            ← Kembali ke produk
        </a>

        <h1>Pesan <?=e($product['name'])?></h1>

        <p>
            Isi data berikut dengan lengkap. Pesanan akan tercatat
            dan WhatsApp terbuka untuk konfirmasi kepada admin.
        </p>

        <?php if ($error): ?>
            <p class="error">
                <?=e($error)?>
            </p>
        <?php endif; ?>

        <div class="info-box">
            Harga referensi:
            <b><?=rupiah($product['price'])?>/kg</b>.

            Harga dan ketersediaan perlu dikonfirmasi admin.
        </div>

        <form method="post" class="edit-form">

            <label>
                Nama lengkap
                <input
                    type="text"
                    name="customer_name"
                    value="<?=e($_POST['customer_name'] ?? '')?>"
                    required
                >
            </label>

            <label>
                Nomor WhatsApp
                <input
                    type="text"
                    name="phone"
                    value="<?=e($_POST['phone'] ?? '')?>"
                    inputmode="numeric"
                    pattern="[0-9]{1,13}"
                    maxlength="13"
                    title="Masukkan angka saja, maksimal 13 digit."
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                    required
                >
            </label>

            <label>
                Alamat lengkap
                <input
                    type="text"
                    name="address"
                    value="<?=e($_POST['address'] ?? '')?>"
                    placeholder="Contoh: Jalan Mawar No. 12, Mataram"
                    required
                >
                <small>
                    Wajib mencantumkan nama jalan, nomor rumah,
                    kelurahan/desa, dan kota.
                </small>
            </label>

            <label>
                Jumlah (kg)
                <input
                    type="number"
                    name="quantity"
                    min="1"
                    max="<?=(int) $product['stock']?>"
                    value="<?=e($_POST['quantity'] ?? '')?>"
                    required
                >
            </label>

            <label>
                Catatan (opsional)
                <textarea
                    name="notes"
                    rows="3"
                ><?=e($_POST['notes'] ?? '')?></textarea>
            </label>

            <button type="submit" class="btn">
                Kirim Pesanan &amp; Lanjut WhatsApp
            </button>

        </form>

    </main>

</body>

</html>
