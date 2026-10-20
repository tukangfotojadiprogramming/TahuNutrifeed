<?php
require '../config.php';
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$products = getProducts($pdo);
$orderCount = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('Baru','Diproses')")->fetchColumn();
$totalProducts = count($products);
$totalStock = (int)$pdo->query("SELECT COALESCE(SUM(stock),0) FROM products")->fetchColumn();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard Admin | TahuNutrifeed</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<header class="nav"><a class="brand" href="../index.php">🌱 TahuNutrifeed <small>Admin</small></a><nav><a href="index.php" class="active">Dashboard</a><a href="products.php">Produk</a><a href="orders.php">Pesanan</a><a href="profile.php">Profil Usaha</a></nav><a class="btn small" href="logout.php">Keluar</a></header>
<main class="section"><span class="eyebrow">PANEL ADMIN</span><h1>Dashboard</h1><p>Ringkasan pengelolaan TahuNutrifeed.</p>
<div class="admin-stats"><article><span>📦</span><small>Total produk</small><strong><?=$totalProducts?></strong><a href="products.php">Kelola produk →</a></article><article><span>🌾</span><small>Total stok tercatat</small><strong><?=$totalStock?></strong><a href="products.php">Perbarui stok →</a></article><article><span>🛒</span><small>Total pesanan</small><strong><?=$orderCount?></strong><a href="orders.php">Lihat pesanan →</a></article><article><span>⏳</span><small>Pesanan perlu ditangani</small><strong><?=$pendingCount?></strong><a href="orders.php">Tindak lanjuti →</a></article></div>
<div class="admin-shortcuts"><a href="products.php"><b>📦 Kelola Produk</b><span>Tambah, edit, hapus produk, harga, foto dan stok.</span></a><a href="orders.php"><b>🛒 Kelola Pesanan</b><span>Catat pesanan pelanggan dan perbarui statusnya.</span></a><a href="profile.php"><b>🏢 Profil Usaha</b><span>Ubah informasi usaha yang tampil di website.</span></a></div>
<p class="note">Catatan: data ringkasan berdasarkan data yang tersimpan. Pesanan WhatsApp tidak tercatat otomatis kecuali admin mencatatnya melalui menu Pesanan.</p></main></body></html>