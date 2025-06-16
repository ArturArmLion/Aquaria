<?php
require 'db.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$stmt = $pdo->prepare("SELECT id, name, description, image, price FROM products WHERE category_id = ?");
$stmt->execute([$category_id]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);
?>
