<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    header("Location: user-profile.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

$stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);

header("Location: user-profile.php");
