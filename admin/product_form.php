<?php
require '../config.php';
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); $p=['name'=>'','description'=>'','price'=>'','stock'=>'','image'=>'']; 
if($id){$s=$pdo->prepare("SELECT * FROM products WHERE id=?");$s->execute([$id]);$found=$s->fetch();if($found)$p=$found;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??'');$desc=trim($_POST['description']??'');$price=(float)($_POST['price']??0);$stock=(int)($_POST['stock']??0);$image=trim($_POST['image']??'');
  if($name===''||$desc===''||$price<0||$stock<0){$error='Lengkapi data dengan benar.';}
  else {if($id){$s=$pdo->prepare("UPDATE products SET name=?,description=?,price=?,stock=?,image=? WHERE id=?");$s->execute([$name,$desc,$price,$stock,$image,$id]);}else{$s=$pdo->prepare("INSERT INTO products(name,description,price,stock,image) VALUES(?,?,?,?,?)");$s->execute([$name,$desc,$price,$stock,$image]);}header('Location: index.php');exit;}
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Form Produk | TahuNutrifeed</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="section form-section"><a href="index.php">← Kembali ke dashboard</a><h1><?=$id?'Edit':'Tambah'?> produk</h1><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif; ?><form class="edit-form" method="post"><label>Nama produk<input name="name" required value="<?=e($p['name'])?>"></label><label>Deskripsi<textarea name="description" rows="5" required><?=e($p['description'])?></textarea></label><label>Harga (Rp)<input type="number" name="price" min="0" step="100" required value="<?=e($p['price'])?>"></label><label>Stok<input type="number" name="stock" min="0" required value="<?=e($p['stock'])?>"></label><label>Lokasi gambar (contoh: assets/pakan.jpg)<input name="image" value="<?=e($p['image'])?>"></label><button class="btn">Simpan produk</button></form></main></body></html>
