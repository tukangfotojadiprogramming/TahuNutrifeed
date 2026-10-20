<?php
require '../config.php';
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);if($id){$s=$pdo->prepare("DELETE FROM products WHERE id=?");$s->execute([$id]);}}
header('Location: index.php');exit;
