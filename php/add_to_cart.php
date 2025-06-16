<?php
session_start();
require_once('../CART/cart-function.php'); // путь от php/ до cart-function.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;

    addToCart($product_id, (int)$quantity);

    echo json_encode(['status' => 'ok']);
}
