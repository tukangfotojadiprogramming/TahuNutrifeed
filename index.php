
<?php
require 'config.php';

$products = getProducts($pdo);
$wa = '6287765470125';

// Menghitung total produk yang sudah terjual
$stmt = $pdo->query("
    SELECT product_id, COALESCE(SUM(quantity), 0) AS total_terjual
    FROM orders
    WHERE status = 'Selesai'
    GROUP BY product_id
");

$soldProducts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>TahuNutrifeed | Pakan Ternak dari Ampas Tahu</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        .hero {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .hero-image img {
            width: 100%;
            max-width: 480px;
            height: auto;
            display: block;
            margin-left: auto;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(29, 78, 36, 0.25);
        }

        /* Stok produk yang ditonjolkan */
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

        /* Informasi jumlah produk terjual */
        .product-sold {
            margin: 0 0 12px;
            color: #687568;
            font-size: 13px;
        }

        .product-sold strong {
            color: #356b36;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .hero-image img {
                margin: 0 auto;
                max-width: 380px;
            }
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
        <a href="produk.php">Produk</a>
        <a href="tentang.php">Tentang Kami</a>
        <a href="dampak.php">Dampak</a>
    </nav>

    <a class="btn small"
       href="https://wa.me/<?=e($wa)?>?text=Halo%20TahuNutrifeed%2C%20saya%20ingin%20bertanya%20tentang%20produk"
       target="_blank"
       rel="noopener noreferrer">
        Hubungi Kami ↗
    </a>
</header>

<main>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">DARI LIMBAH MENJADI MANFAAT</span>

            <h1>
                Ampas Tahu,<br>
                <em>Manfaat Baru</em><br>
                untuk Peternakan.
            </h1>

            <p>
                Kami mengembangkan pemanfaatan limbah ampas tahu
                menjadi produk pakan ternak yang memiliki nilai ekonomi.
            </p>

            <div class="actions">
                <a class="btn" href="produk.php">Jelajahi Produk →</a>
                <a class="btn outline" href="tentang.php">Kenali Usaha Kami</a>
            </div>

            <div class="hero-note">
                ♻ Mengurangi limbah · Mendukung ekonomi lokal
            </div>
        </div>

        <div class="hero-image">
            <img
                src="assets/original.jpeg"
                alt="Pakan Campuran Ampas Tahu TahuNutrifeed 25 KG">
        </div>
    </section>

    <!-- MANFAAT -->
    <section class="benefits">
        <div>
            <span>♻</span>
            <b>Manfaatkan Limbah</b>
            <small>Mendorong pemanfaatan ampas tahu</small>
        </div>

        <div>
            <span>🌱</span>
            <b>Lebih Berkelanjutan</b>
            <small>Mendukung upaya pengurangan limbah</small>
        </div>

        <div>
            <span>🤝</span>
            <b>Dukung Usaha Lokal</b>
            <small>Bersama UD. Tahu Yaya</small>
        </div>
    </section>

    <!-- PRODUK UNGGULAN -->
    <section class="section">
        <div class="section-head">
            <div>
                <span class="eyebrow">PILIHAN PRODUK</span>
                <h2>Produk unggulan kami</h2>
                <p>Hubungi kami untuk detail produk dan ketersediaan stok.</p>
            </div>

            <a class="text-link" href="produk.php">
                Lihat semua produk →
            </a>
        </div>

        <div class="product-grid">

            <?php foreach (array_slice($products, 0, 3) as $p): ?>

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
                                95,
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

                        <!-- STOK DITONJOLKAN -->
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
    </section>

    <!-- AJAKAN KERJA SAMA -->
    <section class="cta">
        <div>
            <span class="eyebrow">TERTARIK BEKERJA SAMA?</span>

            <h2>Mari manfaatkan limbah dengan lebih baik.</h2>

            <p>
                Tanyakan produk, stok, dan cara pemesanan
                langsung kepada kami.
            </p>
        </div>

        <a
            class="btn light"
            href="https://wa.me/<?=e($wa)?>?text=Halo%20TahuNutrifeed%2C%20saya%20ingin%20bertanya"
            target="_blank"
            rel="noopener noreferrer">
            Chat WhatsApp ↗
        </a>
    </section>

</main>

<footer>
    <a class="brand" href="index.php">TahuNutrifeed</a>
    <p>UD. Tahu Yaya · Abian Tubuh, Sandubaya, Kota Mataram</p>
</footer>

</body>
</html>
