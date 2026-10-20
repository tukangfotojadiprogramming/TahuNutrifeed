<?php
require '../config.php';
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$allowed=['Baru','Diproses','Selesai','Dibatalkan'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(isset($_POST['delete_id'])){$s=$pdo->prepare("DELETE FROM orders WHERE id=?");$s->execute([(int)$_POST['delete_id']]);}
 else {$id=(int)($_POST['id']??0);$status=$_POST['status']??'Baru';if(in_array($status,$allowed,true)){$s=$pdo->prepare("UPDATE orders SET status=? WHERE id=?");$s->execute([$status,$id]);}}
 header('Location: orders.php');exit;
}
$orders=$pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Kelola Pesanan | TahuNutrifeed</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<header class="nav"><a class="brand" href="../index.php">🌱 TahuNutrifeed <small>Admin</small></a><nav><a href="index.php">Dashboard</a><a href="products.php">Produk</a><a class="active" href="orders.php">Pesanan</a><a href="profile.php">Profil Usaha</a></nav><a class="btn small" href="logout.php">Keluar</a></header>
<main class="section"><div class="section-head"><div><span class="eyebrow">MANAJEMEN PESANAN</span><h1>Pesanan Pelanggan</h1><p>Pesanan dari form website atau pesanan yang dicatat admin.</p></div><a class="btn" href="order_form.php">+ Catat Pesanan</a></div>
<div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Pelanggan</th><th>Detail</th><th>Kontak</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php if(!$orders): ?><tr><td colspan="6">Belum ada pesanan. Admin bisa menambahkan pesanan yang diterima melalui WhatsApp.</td></tr><?php endif; ?>
<?php foreach($orders as $o): ?><tr><td><?=e($o['created_at'])?></td><td><b><?=e($o['customer_name'])?></b></td><td><?=e($o['product_name'])?><br><?= (int)$o['quantity']?> kg · <?=rupiah($o['total_price'])?></td><td><?=e($o['phone'])?><br><small><?=e($o['address'])?></small></td><td><form method="post" class="status-form"><input type="hidden" name="id" value="<?=(int)$o['id']?>"><select name="status"><?php foreach($allowed as $st): ?><option <?=$o['status']===$st?'selected':''?>><?=e($st)?></option><?php endforeach; ?></select><button class="btn tiny">Simpan</button></form></td><td><form method="post" onsubmit="return confirm('Hapus catatan pesanan ini?')"><input type="hidden" name="delete_id" value="<?=(int)$o['id']?>"><button class="btn danger tiny">Hapus</button></form></td></tr><?php endforeach; ?>
</tbody></table></div></main></body></html>