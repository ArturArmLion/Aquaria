<?php
session_start();
header('Content-Type: application/json');
require('../php/db.php');

$discount = 0;
$message = '';
$cart_total = 0;
$item_total = 0;
$quantity = 0;

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$action = $_POST['action'] ?? 'none';

// Обновляем количество товара в корзине
if ($product_id > 0 && in_array($action, ['increase', 'decrease'])) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0;
    }
    if ($action === 'increase') {
        $_SESSION['cart'][$product_id]++;
    } elseif ($action === 'decrease') {
        $_SESSION['cart'][$product_id]--;
        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

$quantity = $_SESSION['cart'][$product_id] ?? 0;

// Подсчет общей суммы корзины
foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$id]);
    if ($row = $stmt->fetch()) {
        $price = $row['price'];
        if ($id == $product_id) {
            $item_total = $price * $qty;
        }
        $cart_total += $price * $qty;
    }
}

// Обработка промокода или карты лояльности
if (isset($_POST['promo_code'])) {
    $promo = strtoupper(trim($_POST['promo_code']));

    if ($promo === '') {
        $message = 'У вас ничего не введено.';
    } else {
        // Список валидных промокодов
        $valid_promos = [
            'AQUA10' => 0.10,
            'PAXAN' => 0.50,
            'PROMO10' => 0.10,
            'LOYALTY20' => 0.20
        ];

        if (array_key_exists($promo, $valid_promos)) {
            // Обычный промокод
            $discount = $valid_promos[$promo];
            $_SESSION['promo_applied'] = $promo;
            $message = 'Промокод успешно применен!';
        } else {
            // Проверим карту лояльности
            $stmt = $pdo->prepare("SELECT discount_rate FROM loyalty_cards WHERE UPPER(card_number) = ?");
            $stmt->execute([$promo]);
            $card = $stmt->fetch();

            if ($card && (float)$card['discount_rate'] > 0) {
                $discount = (float)$card['discount_rate'];
                $_SESSION['promo_applied'] = $promo;
                $message = 'Карта лояльности успешно применена!';
            } else {
                unset($_SESSION['promo_applied']);
                $message = 'Промокод не найден или неактивен.';
            }
        }
    }
}

$final_price = $cart_total * (1 - $discount);

echo json_encode([
    'success' => true,
    'message' => $message,
    'quantity' => $quantity,
    'item_total' => $item_total,
    'cart_total' => $cart_total,
    'discount' => $discount,
    'final_price' => $final_price
]);
?>