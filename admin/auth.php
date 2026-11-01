<?php
require_once __DIR__ . '/../config.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}

function check_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        http_response_code(403);
        exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

function admin_header(string $title, string $active = 'dashboard'): void {
    $menus = [
        'dashboard' => ['Dashboard', 'index.php', '⌂'],
        'orders' => ['Pesanan', 'orders.php', '▤'],
        'products' => ['Produk', 'products.php', '▦'],
        'finance' => ['Keuangan & Pembukuan', 'finance.php', 'Rp'],
    ];
    ?>
    <!doctype html>
    <html lang="id">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= e($title) ?> | Admin TahuNutrifeed</title>
      <link rel="stylesheet" href="admin.css">
    </head>
    <body>
    <aside class="sidebar">
      <a class="brand" href="index.php"><span class="brand-mark">♻</span><span><b>TahuNutrifeed</b><small>ADMIN PANEL</small></span></a>
      <div class="nav-label">MENU UTAMA</div>
      <?php foreach ($menus as $key => $menu): ?>
        <a class="side-link <?= $active === $key ? 'active' : '' ?>" href="<?= e($menu[1]) ?>">
          <span class="nav-icon"><?= e($menu[2]) ?></span><?= e($menu[0]) ?>
        </a>
      <?php endforeach; ?>
      <div class="sidebar-bottom">
        <div class="admin-chip"><span class="avatar">A</span><span><b><?= e($_SESSION['admin_username'] ?? 'Admin') ?></b><small>Administrator</small></span></div>
        <a class="side-link logout" href="logout.php">↪ Keluar</a>
      </div>
    </aside>
    <main class="main">
      <header class="topbar"><span>Panel Pengelolaan Usaha</span><span class="top-admin">● &nbsp; Admin</span></header>
      <section class="page">
    <?php
}

function admin_footer(): void {
    ?>
      </section>
      <footer class="footer">TahuNutrifeed · UD. Tahu Yaya</footer>
    </main>
    </body>
    </html>
    <?php
}
