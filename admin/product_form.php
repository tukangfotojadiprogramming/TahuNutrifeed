
<?php
require_once __DIR__ . '/auth.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$product = [
    'name' => '',
    'description' => '',
    'price' => '',
    'cost_per_pack' => '',
    'stock' => '',
    'image' => ''
];

$error = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();

    if (!$found) {
        http_response_code(404);
        exit('Produk tidak ditemukan. <a href="products.php">Kembali</a>');
    }

    $product = $found;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $product['name'] = trim($_POST['name'] ?? '');
    $product['description'] = trim($_POST['description'] ?? '');
    $product['price'] = trim($_POST['price'] ?? '');
    $product['cost_per_pack'] = trim($_POST['cost_per_pack'] ?? '');
    $product['stock'] = trim($_POST['stock'] ?? '');
    $product['image'] = trim($_POST['image'] ?? '');

    if (
        $product['name'] === '' ||
        $product['description'] === ''
    ) {
        $error = 'Nama dan deskripsi produk wajib diisi.';
    } elseif (
        !is_numeric($product['price']) ||
        (float)$product['price'] <= 0
    ) {
        $error = 'Harga jual harus berupa angka lebih dari nol.';
    } elseif (
        $product['cost_per_pack'] === '' ||
        !is_numeric($product['cost_per_pack']) ||
        (float)$product['cost_per_pack'] < 0
    ) {
        $error = 'Modal per kemasan harus berupa angka nol atau lebih.';
    } elseif (
        filter_var($product['stock'], FILTER_VALIDATE_INT) === false ||
        (int)$product['stock'] < 0
    ) {
        $error = 'Stok harus berupa bilangan bulat nol atau lebih.';
    } else {
        $price = (float)$product['price'];
        $cost = (float)$product['cost_per_pack'];
        $stock = (int)$product['stock'];

        if ($id) {
            $stmt = $pdo->prepare("
                UPDATE products
                SET name = ?,
                    description = ?,
                    price = ?,
                    cost_per_pack = ?,
                    stock = ?,
                    image = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $product['name'],
                $product['description'],
                $price,
                $cost,
                $stock,
                $product['image'],
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO products
                (name, description, price, cost_per_pack, stock, image)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $product['name'],
                $product['description'],
                $price,
                $cost,
                $stock,
                $product['image']
            ]);
        }

        header('Location: products.php?saved=1');
        exit;
    }
}

admin_header($id ? 'Edit Produk' : 'Tambah Produk', 'products');
?>

<div class="page-heading">
    <div>
        <p class="eyebrow">KATALOG USER</p>
        <h1><?= $id ? 'Edit Produk' : 'Tambah Produk' ?></h1>
        <p class="muted">
            Lengkapi modal, harga jual, stok, dan informasi produk.
        </p>
    </div>
    <a class="btn btn-light" href="products.php">← Kembali</a>
</div>

<?php if ($error): ?>
    <div class="alert danger"><?= e($error) ?></div>
<?php endif; ?>

<section class="panel form-panel">
    <form method="post" class="product-form">
        <?= csrf_field() ?>

        <div class="field full-field">
            <label for="name">Nama Produk *</label>
            <input
                id="name"
                name="name"
                value="<?= e($product['name']) ?>"
                required
                maxlength="150"
                placeholder="Contoh: Pelet Pakan Campuran Ampas Tahu"
            >
        </div>

        <div class="field full-field">
            <label for="description">Deskripsi Produk *</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                required
                placeholder="Jelaskan produk secara singkat"
            ><?= e($product['description']) ?></textarea>
        </div>

        <div class="field">
            <label for="cost_per_pack">Modal per Kemasan (Rp) *</label>
            <input
                id="cost_per_pack"
                name="cost_per_pack"
                type="number"
                min="0"
                step="0.01"
                value="<?= e((string)$product['cost_per_pack']) ?>"
                required
                placeholder="Contoh: 3000"
            >
            <small class="muted">
                Biaya produksi untuk satu kemasan.
            </small>
        </div>

        <div class="field">
            <label for="price">Harga Jual per Kemasan (Rp) *</label>
            <input
                id="price"
                name="price"
                type="number"
                min="0.01"
                step="0.01"
                value="<?= e((string)$product['price']) ?>"
                required
                placeholder="Contoh: 5000"
            >
        </div>

        <div class="field">
            <label for="stock">Stok (kg) *</label>
            <input
                id="stock"
                name="stock"
                type="number"
                min="0"
                step="1"
                value="<?= e((string)$product['stock']) ?>"
                required
            >
        </div>

        <div class="field full-field">
            <label for="image">Lokasi gambar (opsional)</label>
            <input
                id="image"
                name="image"
                value="<?= e($product['image']) ?>"
                placeholder="Contoh: assets/campuran.jpeg"
            >
            <small class="muted">
                Masukkan lokasi relatif dari folder utama website,
                bukan URL komputer lokal.
            </small>
        </div>

        <?php if (!empty($product['image'])): ?>
            <div class="field full-field">
                <label>Pratinjau gambar saat ini</label>
                <img
                    class="preview-image"
                    src="../<?= e($product['image']) ?>"
                    alt="Pratinjau"
                    onerror="this.style.display='none'"
                >
            </div>
        <?php endif; ?>

        <div class="form-actions full-field">
            <button class="btn" type="submit">Simpan Produk</button>
            <a class="btn btn-light" href="products.php">Batal</a>
        </div>
    </form>
</section>

<?php admin_footer(); ?>
