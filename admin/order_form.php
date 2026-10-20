<?php
require '../config.php';
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$products=getProducts($pdo);$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $customer=trim($_POST['customer_name']??'');$phone=trim($_POST['phone']??'');$address=trim($_POST['address']??'');$productId=(int)($_POST['product_id']??0);$qty=(int)($_POST['quantity']??0);$notes=trim($_POST['notes']??'');
 $s=$pdo->prepare("SELECT * FROM products WHERE id=?");$s->execute([$productId]);$product=$s->fetch();
 if(!$customer||!$phone||!$product||$qty<1){$error='Lengkapi nama, nomor WhatsApp, produk, dan jumlah dengan benar.';}
 else {$total=$qty*(float)$product['price'];$s=$pdo->prepare("INSERT INTO orders(customer_name,phone,address,product_id,product_name,quantity,total_price,status,notes) VALUES(?,?,?,?,?,?,?,'Baru',?)");$s->execute([$customer,$phone,$address,$productId,$product['name'],$qty,$total,$notes]);header('Location: orders.php');exit;}
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Catat Pesanan | TahuNutrifeed</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="section form-section"><a href="orders.php">← Kembali ke pesanan</a><h1>Catat Pesanan Pelanggan</h1><p>Gunakan form ini untuk mencatat pesanan yang diterima lewat WhatsApp atau telepon.</p><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif; ?><form class="edit-form" method="post"><label>Nama pelanggan<input name="customer_name" required></label><label>Nomor WhatsApp<input name="phone" required></label><label>Alamat (opsional)<input name="address"></label><label>Produk<select name="product_id" required><?php foreach($products as $p): ?><option value="<?=(int)$p['id']?>"><?=e($p['name'])?> — <?=rupiah($p['price'])?>/kg</option><?php endforeach; ?></select></label><label>Jumlah (kg)<input type="number" name="quantity" min="1" required></label><label>Catatan<textarea name="notes" rows="3"></textarea></label><button class="btn">Simpan Pesanan</button></form></main></body></html>