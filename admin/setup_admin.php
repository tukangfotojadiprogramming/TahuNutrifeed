<?php
require_once __DIR__ . '/../config.php';

$count = (int)$pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
$error = '';
$success = false;

if ($count > 0) {
    http_response_code(403);
    exit('Admin sudah tersedia. Demi keamanan, hapus file setup_admin.php dari folder admin.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($username) < 4) {
        $error = 'Username minimal 4 karakter.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        $success = true;
    }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Buat Admin TahuNutrifeed</title><link rel="stylesheet" href="admin.css"></head>
<body class="login-page"><div class="login-card">
<a class="brand login-brand" href="../index.php"><span class="brand-mark">♻</span><span><b>TahuNutrifeed</b><small>PENGATURAN AWAL</small></span></a>
<?php if ($success): ?>
<div class="alert success">Akun admin berhasil dibuat. Sekarang hapus file <b>setup_admin.php</b> dari folder admin, lalu login.</div>
<a class="btn full" href="login.php">Ke Halaman Login</a>
<?php else: ?>
<h1>Buat Akun Admin</h1><p class="muted">Halaman ini hanya bisa digunakan saat belum ada akun admin.</p>
<?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="stack">
<label>Username<input name="username" required minlength="4" autocomplete="username"></label>
<label>Password (minimal 8 karakter)<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
<label>Ulangi Password<input type="password" name="confirm_password" required minlength="8" autocomplete="new-password"></label>
<button class="btn" type="submit">Buat Akun Admin</button>
</form>
<?php endif; ?>
</div></body></html>
