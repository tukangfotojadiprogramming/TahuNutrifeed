<?php
require '../config.php';
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$defaults=['business_name'=>'UD. Tahu Yaya','address'=>'Kelurahan Abian Tubuh, Kecamatan Sandubaya, Kota Mataram','description'=>'Usaha produksi tahu yang mengembangkan pemanfaatan limbah ampas tahu menjadi pakan ternak bernilai ekonomis.','phone'=>'6281234567890','email'=>''];
$profile=$defaults;
foreach($pdo->query("SELECT setting_key,setting_value FROM settings")->fetchAll() as $r){$profile[$r['setting_key']]=$r['setting_value'];}
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 foreach(['business_name','address','description','phone','email'] as $key){$val=trim($_POST[$key]??'');$s=$pdo->prepare("INSERT INTO settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");$s->execute([$key,$val]);$profile[$key]=$val;}
 $msg='Profil usaha berhasil disimpan.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Profil Usaha | TahuNutrifeed</title><link rel="stylesheet" href="../assets/style.css"></head><body><header class="nav"><a class="brand" href="../index.php">🌱 TahuNutrifeed <small>Admin</small></a><nav><a href="index.php">Dashboard</a><a href="products.php">Produk</a><a href="orders.php">Pesanan</a><a class="active" href="profile.php">Profil Usaha</a></nav><a class="btn small" href="logout.php">Keluar</a></header><main class="section form-section"><span class="eyebrow">INFORMASI USAHA</span><h1>Profil Usaha</h1><p>Ubah informasi yang ditampilkan di website.</p><?php if($msg): ?><p class="success"><?=e($msg)?></p><?php endif; ?><form method="post" class="edit-form"><label>Nama usaha<input name="business_name" required value="<?=e($profile['business_name'])?>"></label><label>Alamat<input name="address" required value="<?=e($profile['address'])?>"></label><label>Deskripsi<textarea name="description" rows="5" required><?=e($profile['description'])?></textarea></label><label>Nomor WhatsApp (format 62)<input name="phone" value="<?=e($profile['phone'])?>"></label><label>Email (opsional)<input type="email" name="email" value="<?=e($profile['email'])?>"></label><button class="btn">Simpan Profil</button></form></main></body></html>