<?php
require '../config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt=$pdo->prepare("SELECT * FROM admins WHERE username=?"); $stmt->execute([trim($_POST['username'] ?? '')]); $admin=$stmt->fetch();
    if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) {
        session_regenerate_id(true); $_SESSION['admin_id']=$admin['id']; $_SESSION['admin_user']=$admin['username']; header('Location: index.php'); exit;
    } else $error='Username atau password salah.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login Admin | TahuNutrifeed</title><link rel="stylesheet" href="../assets/style.css"></head><body class="login-page"><form class="login-card" method="post"><a class="brand" href="../index.php">✿ TahuNutrifeed</a><h1>Login Admin</h1><p>Masuk untuk mengelola katalog produk.</p><?php if($error): ?><p class="error"><?=e($error)?></p><?php endif; ?><label>Username<input name="username" required autocomplete="username"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="btn full">Masuk</button><a href="../index.php">← Kembali ke website</a></form></body></html>
