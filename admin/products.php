<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    check_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
    header('Location: products.php?deleted=1');
    exit;
}

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $like = "%$q%";
    $stmt->execute([$like, $like]);
    $products = $stmt->fetchAll();
} else {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
}
admin_header('Kelola Produk', 'products');
?>
<div class="page-heading"><div><p class="eyebrow">KATALOG USER</p><h1>Kelola Produk</h1><p class="muted">Perubahan di sini akan memengaruhi produk yang ditampilkan pada halaman user.</p></div><a class="btn" href="product_form.php">＋ Tambah Produk</a></div>
<?php if (isset($_GET['saved'])): ?><div class="alert success">Data produk berhasil disimpan.</div><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><div class="alert success">Produk berhasil dihapus.</div><?php endif; ?>
<section class="panel">
  <div class="panel-head"><div><h2>Daftar Produk</h2><p class="muted"><?= count($products) ?> produk ditemukan</p></div>
  <form class="search-form" method="get"><input name="q" value="<?= e($q) ?>" placeholder="Cari nama produk..."><button class="btn btn-light" type="submit">Cari</button></form></div>
  <div class="table-wrap"><table><thead><tr><th>Produk</th><th>Harga per kg</th><th>Stok</th><th>Ditambahkan</th><th>Aksi</th></tr></thead><tbody>
  <?php if (!$products): ?><tr><td colspan="5" class="empty">Produk tidak ditemukan.</td></tr><?php endif; ?>
  <?php foreach ($products as $p): ?><tr>
    <td><div class="product-cell"><?php if (!empty($p['image'])): ?><img src="../<?= e($p['image']) ?>" alt=""><?php else: ?><span class="product-placeholder">🌾</span><?php endif; ?><span><b><?= e($p['name']) ?></b><small class="subline"><?= e(mb_strimwidth($p['description'], 0, 90, '…')) ?></small></span></div></td>
    <td><?= rupiah($p['price']) ?></td><td><?= (int)$p['stock'] ?> kg</td>
    <td><?= e(date('d/m/Y', strtotime($p['created_at']))) ?></td>
    <td><div class="actions-inline"><a class="btn btn-small btn-light" href="product_form.php?id=<?= (int)$p['id'] ?>">Edit</a>
      <form method="post" onsubmit="return confirm('Yakin ingin menghapus produk ini?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn-small btn-danger" type="submit">Hapus</button></form></div></td>
  </tr><?php endforeach; ?>
  </tbody></table></div>
</section>
<?php admin_footer(); ?>
