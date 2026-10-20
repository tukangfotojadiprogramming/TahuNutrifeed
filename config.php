<?php
// Sesuaikan dengan pengaturan MySQL lokal Anda.
$host = 'localhost';
$db   = 'tahunutrifeed';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    exit("Koneksi database gagal. Pastikan MySQL aktif dan database tahunutrifeed sudah dibuat. Detail: " . htmlspecialchars($e->getMessage()));
}
if (session_status() === PHP_SESSION_NONE) session_start();

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function rupiah($value) { return 'Rp ' . number_format((float)$value, 0, ',', '.'); }
function getProducts(PDO $pdo) {
    return $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
}
