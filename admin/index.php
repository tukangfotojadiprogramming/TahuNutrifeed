<?php
require_once __DIR__ . '/auth.php';

$productCount = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$newOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Baru'")->fetchColumn();
$processingOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Diproses'")->fetchColumn();
$completedOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Selesai'")->fetchColumn();
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC, id DESC LIMIT 5")->fetchAll();
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 5")->fetchAll();

admin_header('Dashboard', 'dashboard');
?>
<div class="page-heading"><div><p class="eyebrow">RINGKASAN USAHA</p><h1>Dashboard Admin</h1><p class="muted">Kelola produk dan pesanan TahuNutrifeed dari satu tempat.</p></div><span class="date-pill"><?= date('d M Y') ?></span></div>

<div class="stats-grid">
  <article class="stat-card green"><span class="stat-icon">▦</span><div><p>Produk</p><strong><?= $productCount ?></strong><small>Produk terdaftar</small></div></article>
  <article class="stat-card amber"><span class="stat-icon">▤</span><div><p>Pesanan Baru</p><strong><?= $newOrders ?></strong><small>Menunggu ditinjau</small></div></article>
  <article class="stat-card blue"><span class="stat-icon">↻</span><div><p>Pesanan Diproses</p><strong><?= $processingOrders ?></strong><small>Sedang ditangani</small></div></article>
  <article class="stat-card mint"><span class="stat-icon">✓</span><div><p>Pesanan Selesai</p><strong><?= $completedOrders ?></strong><small>Telah diselesaikan</small></div></article>
</div>

<section class="panel">
  <div class="panel-head"><div><h2>Pesanan Terbaru</h2><p class="muted">Pesanan yang dikirim melalui formulir website.</p></div><a class="btn btn-light" href="orders.php">Lihat Semua →</a></div>
  <div class="table-wrap"><table><thead><tr><th>No. Pesanan</th><th>Pemesan</th><th>Produk</th><th>Jumlah</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
  <?php if (!$orders): ?><tr><td colspan="7" class="empty">Belum ada pesanan.</td></tr><?php endif; ?>
  <?php foreach ($orders as $o): ?><tr>
    <td><b>#TNF-<?= (int)$o['id'] ?></b><small class="subline"><?= e(date('d/m/Y H:i', strtotime($o['created_at']))) ?></small></td>
    <td><?= e($o['customer_name']) ?><small class="subline"><?= e($o['phone']) ?></small></td>
    <td><?= e($o['product_name']) ?></td><td><?= (int)$o['quantity'] ?> kg</td><td><?= rupiah($o['total_price']) ?></td>
    <td><span class="status <?= e(strtolower($o['status'])) ?>"><?= e($o['status']) ?></span></td>
    <td><a class="table-action" href="orders.php?id=<?= (int)$o['id'] ?>">Detail</a></td>
  </tr><?php endforeach; ?>
  </tbody></table></div>
</section>

<section class="panel">
  <div class="panel-head"><div><h2>Produk Dikelola</h2><p class="muted">Produk yang tersedia di katalog user.</p></div><a class="btn" href="product_form.php">＋ Tambah Produk</a></div>
  <div class="table-wrap"><table><thead><tr><th>Produk</th><th>Harga/kg</th><th>Stok</th><th>Kondisi Stok</th><th></th></tr></thead><tbody>
  <?php if (!$products): ?><tr><td colspan="5" class="empty">Belum ada produk.</td></tr><?php endif; ?>
  <?php foreach ($products as $p): ?><tr>
    <td><div class="product-cell"><?php if (!empty($p['image'])): ?><img src="../<?= e($p['image']) ?>" alt=""><?php else: ?><span class="product-placeholder">🌾</span><?php endif; ?><span><b><?= e($p['name']) ?></b><small class="subline"><?= e(mb_strimwidth($p['description'], 0, 65, '…')) ?></small></span></div></td>
    <td><?= rupiah($p['price']) ?></td><td><?= (int)$p['stock'] ?> kg</td>
    <td><span class="status <?= (int)$p['stock'] <= 10 ? 'baru' : 'selesai' ?>"><?= (int)$p['stock'] <= 10 ? 'Stok menipis' : 'Tersedia' ?></span></td>
    <td><a class="table-action" href="product_form.php?id=<?= (int)$p['id'] ?>">Edit</a></td>
  </tr><?php endforeach; ?>
  </tbody></table></div>
</section>
<?php admin_footer(); ?>
