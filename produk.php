<?php
require 'config.php';
$q = trim($_GET['q'] ?? '');
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
$like = "%$q%"; $stmt->execute([$like,$like]); $products = $stmt->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Produk | TahuNutrifeed</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="nav"><a class="brand" href="index.php"><span class="leaf">✿</span> TahuNutrifeed</a><nav><a href="index.php">Beranda</a><a class="active" href="produk.php">Produk</a><a href="tentang.php">Tentang Kami</a><a href="dampak.php">Dampak</a></nav><a class="btn small" href="https://wa.me/6281234567890" target="_blank">Hubungi Kami ↗</a></header>
<section class="page-banner"><span class="eyebrow">KATALOG TAHUNUTRIFEED</span><h1>Produk untuk kebutuhan ternak</h1><p>Informasi harga dan stok dapat dikonfirmasi langsung kepada pengelola.</p></section>
<main class="section"><form class="search" method="get"><input name="q" value="<?=e($q)?>" placeholder="Cari produk..."><button class="btn">Cari produk</button></form><div class="product-grid">
<?php if (!$products): ?><p>Produk tidak ditemukan.</p><?php endif; ?>
<?php foreach ($products as $p): ?><article class="product"><a href="detail.php?id=<?=(int)$p['id']?>"><?php if (!empty($p['image']) && file_exists(__DIR__.'/'.$p['image'])): ?><img src="<?=e($p['image'])?>" alt="<?=e($p['name'])?>"><?php else: ?><div class="product-placeholder">🌾</div><?php endif; ?></a><div class="product-body"><h3><?=e($p['name'])?></h3><p><?=e(mb_strimwidth($p['description'],0,120,'…'))?></p><div class="price"><?=rupiah($p['price'])?> <small>/ kg (konfirmasi)</small></div><p class="stock">Stok tercatat: <?= (int)$p['stock'] ?></p><a class="btn full" href="detail.php?id=<?=(int)$p['id']?>">Lihat detail</a></div></article><?php endforeach; ?>
</div></main><footer><a class="brand" href="index.php">✿ TahuNutrifeed</a><p>UD. Tahu Yaya · Abian Tubuh, Sandubaya, Kota Mataram</p></footer></body></html>
