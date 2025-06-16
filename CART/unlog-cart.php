<?php
session_start();
require_once('cart-function.php');
require('../php/db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$showModal = false;

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total_items = array_sum($_SESSION['cart']);
$total_price = 0;
$discount = 0;
$cart_products = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    if ($row = $stmt->fetch()) {
        $row['quantity'] = $quantity;
        $row['total_price'] = $row['price'] * $quantity;
        $total_price += $row['total_price'];
        $cart_products[] = $row;
    }
}

// Удаление товара
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $id = $_POST['product_id'];
    removeFromCart($id);
    header("Location: unlog-cart.php");
    exit;
}

$final_price = $total_price * (1 - $discount);

// Убираем обработку оформления заказа, чтобы заказ не создавался
// и всегда показывать модалку авторизации при нажатии кнопки

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body>

<!-- HEADER -->

<header class="header">
    <div class="container-head">
        <div class="main-menu">
            <ul class="main-menu-top">
                <li class="main-menu__item"><a href="../index.php"><img src="../images/logo.png" width="200px" alt=""></a></li>
            </ul>
            <ul class="main-menu-top-address">
                <li class="main-menu__item"><img src="../images/icon _location_.png" alt=""></li>
                <li class="main-menu__item"><p>ул.Баррикадная д.16</p></li>
            </ul>

            <ul class="main-menu-top-kontakti">
                <li class="main-menu__item"><img src="../images/icon _phone_.png" alt=""></li>
                <li class="main-menu__item"><p>8 (777) 777-77-77</p></li>
            </ul>

            <ul class="main-menu-top-reg">
                <li class="main-menu__item"><a href="backend/register.php"><img src="../images/icon _person_.png" alt=""></a></li>
                <li class="main-menu__item"><a href="backend/register.php"><p>Войти</p></a></li>
            </ul>

            <ul class="main-menu-top-cart">
                <li class="main-menu__item">
                    <a href="cart.php" id="cart-icon">
                        <img src="../images/Cart Software - iconSvg.co.png" alt="">
                        <span id="cart-count" class="cart-count">0</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="cont-2">
            <ul class="main-menu-top2">
                <li class="main-menu__item2"><a class="main-menu-link" href="../index.php">Каталог</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../service.php">Услуги</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../article.php">Статьи</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../kontakti.html">Контакты</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../delivery.html">Доставка</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../kompany.html">О компании</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="container">
    <h1 class="title">Ваша корзина</h1>

    <?php if (count($cart_products) > 0): ?>
    <form class="checkout-form" method="POST" action="unlog-cart.php">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Итого</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $item): ?>
                <tr id="row-<?= $item['id'] ?>">
                    <td><img src="../images/products/<?= htmlspecialchars($item['image']) ?>" width="70"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td>
                        <button type="button" class="qty-btn" data-id="<?= $item['id'] ?>" data-action="decrease">-</button>
                        <span id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                        <button type="button" class="qty-btn" data-id="<?= $item['id'] ?>" data-action="increase">+</button>
                    </td>
                    <td><?= $item['price'] ?> ₽</td>
                    <td id="total-<?= $item['id'] ?>"><?= $item['total_price'] ?> ₽</td>
                    <td>
                        <form method="POST" action="unlog-cart.php" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove_from_cart" class="remove-btn" title="Удалить товар">✖</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary">
            <h3>Сумма заказа:</h3>
            <p>Общая сумма: <strong id="total-price"><?= $total_price ?> ₽</strong></p>
            <p>Скидка: <strong id="discount"><?= $discount * 100 ?>%</strong></p>
            <p>Итого со скидкой: <strong id="final-price"><?= $final_price ?> ₽</strong></p>
        </div>

        <h3>Оплата</h3>
        <label><input type="radio" name="payment_method" value="cash" required> Наличными</label>
        <label><input type="radio" name="payment_method" value="card_on_delivery" required> Картой курьеру</label>

        <h3>Адрес доставки</h3>
        <textarea name="delivery_address" rows="3" required></textarea>

        <button type="button" id="checkout-btn" class="checkout-btn">Оформить заказ</button>
        <input type="hidden" name="checkout" value="1" />
    </form>

    <a href="../index.php"><button class="back-home-btn">На главную</button></a>

    <?php else: ?>
        <p class="empty-cart">Ваша корзина пуста.</p>
        <a href="../index.php"><button class="back-home-btn">На главную</button></a>
    <?php endif; ?>
</div>

<!-- Модальное окно -->
<div id="login-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <p>Пожалуйста, авторизуйтесь для оформления заказа.</p>
        <a href="../backend/login.php"><button>Перейти к авторизации</button></a>
    </div>
</div>

<script>
// Всегда показываем модалку при нажатии кнопки "Оформить заказ"
document.getElementById('checkout-btn').addEventListener('click', function (event) {
    event.preventDefault();
    document.getElementById('login-modal').style.display = 'block';
});

function closeModal() {
    document.getElementById('login-modal').style.display = 'none';
}

// Обработчик кнопок +/- количества товара
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const productId = btn.dataset.id;
        const action = btn.dataset.action;

        fetch('update-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&action=${action}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`qty-${productId}`).textContent = data.quantity;
                document.getElementById(`total-${productId}`).textContent = data.item_total + ' ₽';
                document.getElementById('total-price').textContent = data.cart_total + ' ₽';

                if (data.discount > 0) {
                    document.getElementById('discount').textContent = (data.discount * 100) + '%';
                    document.getElementById('final-price').textContent = data.final_price + ' ₽';
                } else {
                    document.getElementById('discount').textContent = '0%';
                    document.getElementById('final-price').textContent = data.cart_total + ' ₽';
                }

                if (data.quantity <= 0) {
                    document.getElementById(`row-${productId}`).remove();
                }
            }
        });
    });
});

// Обновление счетчика корзины
function updateCartCounter() {
    fetch('../php/cart_count.php')
        .then(res => res.json())
        .then(data => {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const count = data.count || 0;
                cartCount.textContent = count > 0 ? count : '';
            }
        });
}
updateCartCounter();
</script>

</body>
</html>
