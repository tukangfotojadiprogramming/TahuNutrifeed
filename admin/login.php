<?php
require_once __DIR__ . '/../config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin | TahuNutrifeed</title><link rel="stylesheet" href="admin.css">
</head>
<body class="login-page">
<div class="login-card">
  <a class="brand login-brand" href="../index.php"><span class="brand-mark">♻</span><span><b>TahuNutrifeed</b><small>ADMIN PANEL</small></span></a>
  <p class="eyebrow">SELAMAT DATANG KEMBALI</p>
  <h1>Login Admin</h1>
  <p class="muted">Masuk untuk mengelola produk dan pesanan TahuNutrifeed.</p>
  <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="stack">
    <label>Username<input type="text" name="username" required autocomplete="username" autofocus></label>
    <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
    <button class="btn" type="submit">Masuk ke Dashboard →</button>
  </form>
  <a class="back-link" href="../index.php">← Kembali ke website</a>
</div>
</body>
</html>
