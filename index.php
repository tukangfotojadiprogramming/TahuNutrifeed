<?php
require 'config.php';
$products = getProducts($pdo);
$wa = '6281234567890'; // GANTI dengan nomor WhatsApp UD. Tahu Yaya, format 62 tanpa +.
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>TahuNutrifeed | Pakan Ternak dari Ampas Tahu</title>
<link rel="stylesheet" href="assets/style.css?v=3">
</head><body>
<header class="nav"><a class="brand" href="index.php"><span class="leaf">✿</span> TahuNutrifeed</a><nav><a href="index.php">Beranda</a><a href="produk.php">Produk</a><a href="tentang.php">Tentang Kami</a><a href="dampak.php">Dampak</a></nav><a class="btn small" href="https://wa.me/<?=e($wa)?>?text=Halo%20TahuNutrifeed%2C%20saya%20ingin%20bertanya%20tentang%20produk" target="_blank">Hubungi Kami ↗</a></header>
<main>
<section class="hero"><div class="hero-text"><span class="eyebrow">DARI LIMBAH MENJADI MANFAAT</span><h1>Ampas Tahu,<br><em>Manfaat Baru</em><br>untuk Peternakan.</h1><p>Kami mengembangkan pemanfaatan limbah ampas tahu menjadi produk pakan ternak yang memiliki nilai ekonomi.</p><div class="actions"><a class="btn" href="produk.php">Jelajahi Produk →</a><a class="btn outline" href="tentang.php">Kenali Usaha Kami</a></div><div class="hero-note">♻ Mengurangi limbah · Mendukung ekonomi lokal</div></div><div class="hero-image"><div class="image-card"><img src="https://images.unsplash.com/photo-150059ah?auto=format&fit=crop&w=900&q=80" alt="" onerror="this.style.display='none'"><div class="image-overlay"><b>Lebih bermanfaat.</b><br><span>Lebih berkelanjutan.</span></div></div></div></section>
<section class="benefits"><div><span>♻</span><b>Manfaatkan Limbah</b><small>Mendorong pemanfaatan ampas tahu</small></div><div><span>🌱</span><b>Lebih Berkelanjutan</b><small>Mendukung upaya pengurangan limbah</small></div><div><span>🤝</span><b>Dukung Usaha Lokal</b><small>Bersama UD. Tahu Yaya</small></div></section>
<section class="section"><div class="section-head"><div><span class="eyebrow">PILIHAN PRODUK</span><h2>Produk unggulan kami</h2><p>Hubungi kami untuk detail produk dan ketersediaan stok.</p></div><a class="text-link" href="produk.php">Lihat semua produk →</a></div><div class="product-grid">
<?php foreach (array_slice($products,0,3) as $p): ?><article class="product"><a href="detail.php?id=<?= (int)$p['id'] ?>"><?php if (!empty($p['image']) && file_exists(__DIR__.'/'.$p['image'])): ?><img src="<?=e($p['image'])?>" alt="<?=e($p['name'])?>"><?php else: ?><div class="product-placeholder">🌾</div><?php endif; ?></a><div class="product-body"><h3><?=e($p['name'])?></h3><p><?=e(mb_strimwidth($p['description'],0,95,'…'))?></p><div class="price"><?=rupiah($p['price'])?> <small>/ kg (konfirmasi)</small></div><a class="btn full" href="detail.php?id=<?=(int)$p['id']?>">Lihat detail</a></div></article><?php endforeach; ?>
</div></section>
<section class="cta"><div><span class="eyebrow">TERTARIK BEKERJA SAMA?</span><h2>Mari manfaatkan limbah dengan lebih baik.</h2><p>Tanyakan produk, stok, dan cara pemesanan langsung kepada kami.</p></div><a class="btn light" href="https://wa.me/<?=e($wa)?>?text=Halo%20TahuNutrifeed%2C%20saya%20ingin%20bertanya" target="_blank">Chat WhatsApp ↗</a></section>
</main><footer><a class="brand" href="index.php">✿ TahuNutrifeed</a><p>UD. Tahu Yaya · Abian Tubuh, Sandubaya, Kota Mataram</p></footer></body></html>
