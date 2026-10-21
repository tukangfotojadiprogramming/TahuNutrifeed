<?php
require 'config.php';
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?"); $stmt->execute([$id ?: 0]); $p = $stmt->fetch();
if (!$p) { http_response_code(404); exit('Produk tidak ditemukan. <a href="produk.php">Kembali ke produk</a>'); }
$wa='6287765470125'; // GANTI dengan nomor WhatsApp UD. Tahu Yaya.
$msg = "Halo TahuNutrifeed, saya ingin bertanya/memesan: ".$p['name'].". Mohon informasi harga, berat kemasan, dan ketersediaan stok.";
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($p['name'])?> | TahuNutrifeed</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="nav"><a class="brand" href="index.php"><span class="leaf">♻️</span> TahuNutrifeed</a><nav><a href="index.php">Beranda</a><a href="produk.php">Produk</a><a href="tentang.php">Tentang Kami</a><a href="dampak.php">Dampak</a></nav><a class="btn small" href="https://wa.me/<?=e($wa)?>" target="_blank">Hubungi Kami ↗</a></header>
<main class="section"><p><a href="produk.php">← Kembali ke produk</a></p><div class="detail-grid"><div class="detail-image"><?php if (!empty($p['image']) && file_exists(__DIR__.'/'.$p['image'])): ?><img src="<?=e($p['image'])?>" alt="<?=e($p['name'])?>"><?php else: ?><div class="product-placeholder big">🌾</div><?php endif; ?></div><div><span class="eyebrow">PRODUK TAHUNUTRIFEED</span><h1><?=e($p['name'])?></h1><div class="price large"><?=rupiah($p['price'])?> <small>/ kg (konfirmasi)</small></div><p><?=nl2br(e($p['description']))?></p><div class="info-box"><b>Informasi produk</b><p>Stok tercatat: <?= (int)$p['stock'] ?>. Konfirmasi harga, berat kemasan, komposisi, dan ketersediaan kepada pengelola sebelum membeli.</p></div><a class="btn" href="pesan.php?id=<?=(int)$p['id']?>">Pesan Produk ↗</a> <a class="btn outline" target="_blank" href="https://wa.me/<?=e($wa)?>?text=<?=rawurlencode($msg)?>">Tanya via WhatsApp</a></div></div></main>
<footer><a class="brand" href="index.php">♻️ TahuNutrifeed</a><p>UD. Tahu Yaya · Kota Mataram</p></footer></body></html>
