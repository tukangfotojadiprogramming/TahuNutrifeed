
<?php
require 'config.php';

$q = trim($_GET['q'] ?? '');

$stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE name LIKE ? OR description LIKE ?
    ORDER BY id DESC
");

$like = "%$q%";
$stmt->execute([$like, $like]);
$products = $stmt->fetchAll();

// Menghitung jumlah produk yang terjual
$stmtSold = $pdo->query("
    SELECT product_id, COALESCE(SUM(quantity), 0) AS total_terjual
    FROM orders
    WHERE status = 'Selesai'
    GROUP BY product_id
");

$soldProducts = $stmtSold->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Produk | TahuNutrifeed</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        .product-stock {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 14px 0 10px;
            padding: 12px 14px;
            background: #eaf6e9;
            border: 1px solid #c9e6c6;
            border-radius: 10px;
        }

        .product-stock span {
            color: #356b36;
            font-size: 13px;
        }

        .product-stock strong {
            color: #246b2b;
            font-size: 18px;
            font-weight: 800;
        }

        .product-sold {
            margin: 0 0 12px;
            color: #687568;
            font-size: 13px;
        }

        .product-sold strong {
            color: #356b36;
        }
    </style>
</head>

<body>

<header class="nav">
    <a class="brand" href="index.php">
        <span class="leaf"></span>
        TahuNutrifeed
    </a>

    <nav>
        <a href="index.php">Beranda</a>
        <a class="active" href="produk.php">Produk</a>
        <a href="tentang.php">Tentang Kami</a>
        <a href="dampak.php">Dampak</a>
    </nav>

    <a
        class="btn small"
        href="https://wa.me/6287765470125"
        target="_blank"
        rel="noopener noreferrer">
        Hubungi Kami ↗
    </a>
</header>

<section class="page-banner">
    <span class="eyebrow">KATALOG TAHUNUTRIFEED</span>
    <h1>Produk untuk kebutuhan ternak</h1>
    <p>Informasi harga dan stok dapat dikonfirmasi langsung kepada pengelola.</p>
</section>

<main class="section">

    <!-- PENCARIAN PRODUK -->
    <form class="search" method="get">
        <input
            name="q"
            value="<?=e($q)?>"
            placeholder="Cari produk...">

        <button class="btn">Cari produk</button>
    </form>

    <div class="product-grid">

        <?php if (!$products): ?>
            <p>Produk tidak ditemukan.</p>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>

            <?php
            $productId = (int) $p['id'];
            $totalTerjual = (float) ($soldProducts[$productId] ?? 0);
            $stok = (float) $p['stock'];
            ?>

            <article class="product">

                <a href="detail.php?id=<?=$productId?>">
                    <?php if (
                        !empty($p['image']) &&
                        file_exists(__DIR__ . '/' . $p['image'])
                    ): ?>

                        <img
                            src="<?=e($p['image'])?>"
                            alt="<?=e($p['name'])?>">

                    <?php else: ?>

                        <div class="product-placeholder">🌾</div>

                    <?php endif; ?>
                </a>

                <div class="product-body">

                    <h3><?=e($p['name'])?></h3>

                    <p>
                        <?=e(mb_strimwidth(
                            $p['description'],
                            0,
                            120,
                            '…'
                        ))?>
                    </p>

                    <!-- JUMLAH TERJUAL DI BAWAH DESKRIPSI -->
                    <p class="product-sold">
                        Terjual:
                        <strong>
                            <?=number_format($totalTerjual, 0, ',', '.')?>
                            kg
                        </strong>
                    </p>

                    <!-- HIGHLIGHT STOK -->
                    <div class="product-stock">
                        <span>📦 Stok tersedia</span>

                        <strong>
                            <?=number_format($stok, 0, ',', '.')?>
                            kg
                        </strong>
                    </div>

                    <div class="price">
                        <?=rupiah($p['price'])?>
                        <small>/ kg (konfirmasi)</small>
                    </div>

                    <a
                        class="btn full"
                        href="detail.php?id=<?=$productId?>">
                        Lihat detail
                    </a>

                </div>
            </article>

        <?php endforeach; ?>

    </div>
</main>

<footer>
    <a class="brand" href="index.php">TahuNutrifeed</a>
    <p>UD. Tahu Yaya · Abian Tubuh, Sandubaya, Kota Mataram</p>
</footer>

</body>
</html>
