<?php
session_start();
require_once('cart-function.php');

$ip = $_SERVER['REMOTE_ADDR']; // Получаем IP пользователя
$time = time(); // Текущее время
$timeWindow = 60; // Период времени для ограничения (например, 60 секунд)
$maxRequests = 10; // Максимальное количество запросов за период

// Если сессия существует, проверяем количество запросов
if (!isset($_SESSION['requests'])) {
    $_SESSION['requests'] = [];
}

// Удаляем запросы, которые были сделаны более чем за $timeWindow секунд назад
$_SESSION['requests'] = array_filter($_SESSION['requests'], function ($timestamp) use ($time, $timeWindow) {
    return $timestamp > ($time - $timeWindow);
});

// Добавляем новый запрос в сессию
$_SESSION['requests'][] = $time;

// Проверяем, не превысил ли пользователь лимит запросов
if (count($_SESSION['requests']) > $maxRequests) {
    // Если превышен лимит, показываем ошибку
    die('Too many requests. Please try again later.');
}

// Подключение к БД
require('../php/db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// header("Content-Security-Policy: default-src 'self'; script-src 'self' https://trusted-cdn.com; localhost/ДИПЛОМ/CARTcart.php;");

// Проверка, если пользователь залогинен
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Работа с корзиной
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
    header("Location: cart.php");
    exit;
}

// Обработка промокода
if (isset($_POST['promo_code'])) {
    $promo = trim($_POST['promo_code']);
    $discount = 0;
    $message = '';

    // Проверка на фиксированные промокоды
    if ($promo === 'PROMO10') {
        $discount = 0.10;
        $message = 'Промокод PROMO10 применен.';
    } elseif ($promo === 'LOYALTY20') {
        $discount = 0.20;
        $message = 'Промокод LOYALTY20 применен.';
    } else {
        // Проверка на карту лояльности
        $stmt = $pdo->prepare("SELECT * FROM loyalty_cards WHERE card_number = ?");
        $stmt->execute([$promo]);
        $card = $stmt->fetch();

        if ($card) {
            // Применение скидки на основе карты лояльности
            $discount = 0.20;  // Предположим, что все карты дают скидку 20%
            $message = 'Карта лояльности успешно применена!';
        } else {
            $message = 'Промокод не найден или неактивен.';
        }
    }

    // Выводим сообщение
    echo json_encode([
        'success' => true,
        'message' => $message,
        'discount' => $discount
    ]);

    // Применение скидки к общему расчету
    $final_price = $total_price * (1 - $discount);
}

$final_price = $total_price * (1 - $discount);

// Оформление заказа
if (isset($_POST['checkout'])) {
    $delivery_address = trim($_POST['delivery_address'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    // Проверка, что адрес доставки и способ оплаты заполнены
    if (empty($delivery_address)) {
        $error = 'Пожалуйста, укажите адрес доставки.';
    } elseif (empty($payment_method)) {
        $error = 'Пожалуйста, выберите способ оплаты.';
    } elseif (!in_array($payment_method, ['cash', 'card_on_delivery'])) {
        $error = 'Некорректный способ оплаты.';
    }

    // Если нет ошибок, выполняем оформление заказа
    if (empty($error)) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Цена для каждого товара (с учетом скидки)
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            if ($product) {
                $price_with_discount = $product['price'] * $quantity * (1 - $discount);
                addOrder($product_id, $quantity, $price_with_discount, $delivery_address, $payment_method, $discount);
            }
        }
        // Очистка корзины
        $_SESSION['cart'] = [];

        // Перенаправление на страницу информации о заказе
        header("Location: order-information.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>

<!-- HEADER -->

<header class="header">
    <div class="container-head">
        <div class="main-menu">
            <ul class="main-menu-top">
                <li class="main-menu__item"><a href="../user_index.php"><img src="../images/logo.png" width="200px" alt=""></a></li>
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
                <li class="main-menu__item"><a href="../Profile/user-profile.php"><img src="../images/profile.png" alt=""></a></li>
                <li class="main-menu__item"><a href="../Profile/user-profile.php"><p>Профиль</p></a></li>
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
                <li class="main-menu__item2"><a class="main-menu-link" href="../user_index.php">Каталог</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../login-page/login-service.php">Услуги</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../login-page/login-article.php">Статьи</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../login-page/login-kontakti.html">Контакты</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../login-page/login-delivery.html">Доставка</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="../login-page/login-kompany.html">О компании</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="container">
    <h1 class="title">Ваша корзина</h1>

    <?php if (count($cart_products) > 0): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Фото</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Кол-во</th>
                <th>Цена за шт.</th>
                <th>Итого</th>
                <th>Удалить</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_products as $item): ?>
             <tr id="row-<?= $item['id'] ?>">
                <td><img src="../images/products/<?= htmlspecialchars($item['image']) ?>" width="70" alt="Изображение товара"></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td>
                    <button type="button" class="qty-btn" data-id="<?= $item['id'] ?>" data-action="decrease">−</button>
                    <span id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                    <button type="button" class="qty-btn" data-id="<?= $item['id'] ?>" data-action="increase">+</button>
                </td>
                <td><?= $item['price'] ?> ₽</td>
                <td id="total-<?= $item['id'] ?>"><?= $item['total_price'] ?> ₽</td>
                <td>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="remove_from_cart" class="remove-btn">✖</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <form id="promo-form" class="checkout-form" method="POST" action="cart.php">
        <h3>Промокод / карта лояльности</h3>
        <input type="text" id="promo-code-input" name="promo_code" placeholder="Введите промокод или номер карты" class="input-field">
        <button type="button" id="apply-promo-btn" class="apply-btn">Применить</button>
        <div id="promo-message" style="margin-top: 10px; color: green; font-weight: bold;"></div>
    </form>

    <form method="post" action="cart.php">
        <div class="summary">
            <h3>Сумма заказа:</h3>
            <p>Общая сумма: <strong id="total-price"><?= $total_price ?> ₽</strong></p>
            <p>Скидка: <strong id="discount"><?= $discount * 100 ?>%</strong></p>
            <p>Итого со скидкой: <strong id="final-price"><?= $final_price ?> ₽</strong></p>
        </div>

        <h3>Оплата</h3>
        <div class="input-field">
            <label>
                <input type="radio" name="payment_method" value="cash" required
                    <?= (isset($payment_method) && $payment_method === 'cash') ? 'checked' : '' ?>>
                Наличными курьеру
            </label><br>
            <label>
                <input type="radio" name="payment_method" value="card_on_delivery" required
                    <?= (isset($payment_method) && $payment_method === 'card_on_delivery') ? 'checked' : '' ?>>
                Оплата картой курьеру
            </label>
        </div>

        <h3>Адрес доставки</h3>
        <textarea name="delivery_address" rows="3" class="input-field" required><?= htmlspecialchars($_POST['delivery_address'] ?? '') ?></textarea>

        <?php if (!empty($error)): ?>
            <p class="error" style="color: red; font-weight: bold;"><?= $error ?></p>
        <?php endif; ?>

        <button type="submit" name="checkout" class="checkout-btn">Оформить заказ</button>
    </form>

    <!-- Кнопка на главную -->
    <a href="../user_index.php">
        <button type="button" class="back-home-btn">На Главную</button>
    </a>

    <?php else: ?>
        <p class="empty-cart">Ваша корзина пуста.</p>
            <a href="../user_index.php">
                <button type="button" class="back-home-btn">На Главную</button>
            </a>
    <?php endif; ?>
</div>

    <script>
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
                        // Обновление количества товара
                        document.getElementById(`qty-${productId}`).textContent = data.quantity;

                        // Обновление итоговой суммы товара
                        document.getElementById(`total-${productId}`).textContent = data.item_total + ' ₽';

                        // Обновление общей суммы корзины
                        document.getElementById('total-price').textContent = data.cart_total + ' ₽';

                        // Обновление суммы со скидкой
                        if (data.discount > 0) {
                            document.getElementById('discount').textContent = (data.discount * 100) + '%';
                            document.getElementById('final-price').textContent = data.final_price + ' ₽';
                        } else {
                            document.getElementById('discount').textContent = '0%';
                            document.getElementById('final-price').textContent = data.cart_total + ' ₽';
                        }

                        // Если количество товара стало 0, удаляем строку
                        if (data.quantity <= 0) {
                            document.getElementById(`row-${productId}`).remove();
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('apply-promo-btn').addEventListener('click', function () {
            const promoCode = document.getElementById('promo-code-input').value.trim();
            const messageBox = document.getElementById('promo-message');

            fetch('update-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `promo_code=${encodeURIComponent(promoCode)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total-price').textContent = data.cart_total + ' ₽';
                    document.getElementById('discount').textContent = (data.discount * 100) + '%';
                    document.getElementById('final-price').textContent = data.final_price + ' ₽';

                    // Показать сообщение
                    messageBox.textContent = data.message;
                    messageBox.style.color = data.discount > 0 ? 'green' : 'red';
                } else {
                    messageBox.textContent = 'Ошибка при применении промокода.';
                    messageBox.style.color = 'red';
                }
            })
            .catch(() => {
                messageBox.textContent = 'Ошибка связи с сервером.';
                messageBox.style.color = 'red';
            });
        });
    </script>

    <script>
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


    <script>
    document.getElementById('apply-promo-btn').addEventListener('click', function () {
        const promoCode = document.getElementById('promo-code-input').value.trim();
        const promoMessage = document.getElementById('promo-message');

        if (!promoCode) {
            promoMessage.textContent = 'Введите промокод или номер карты.';
            promoMessage.style.color = 'red';
            return;
        }

        fetch('update-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `promo_code=${encodeURIComponent(promoCode)}`
        })
        .then(res => res.json())
        .then(data => {
            promoMessage.textContent = data.message || 'Обновление прошло успешно.';
            promoMessage.style.color = data.discount > 0 ? 'green' : 'red';

            // Обновим отображение цен на странице
            document.getElementById('total-price').textContent = data.cart_total + ' ₽';
            document.getElementById('discount').textContent = (data.discount * 100) + '%';
            document.getElementById('final-price').textContent = data.final_price + ' ₽';
        })
        .catch(() => {
            promoMessage.textContent = 'Ошибка связи с сервером.';
            promoMessage.style.color = 'red';
        });
    });

    </script>

    <script>
    document.getElementById('checkout-form').addEventListener('submit', function (e) {
        const deliveryAddress = document.querySelector('textarea[name="delivery_address"]').value.trim();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');

        // Проверка, что поле для адреса доставки не пустое
        if (!deliveryAddress) {
            e.preventDefault();  // Останавливаем отправку формы
            alert('Пожалуйста, укажите адрес доставки.');
            return;
        }

        // Проверка, что выбран способ оплаты
        if (!paymentMethod) {
            e.preventDefault();  // Останавливаем отправку формы
            alert('Пожалуйста, выберите способ оплаты.');
            return;
        }
    });
</script>

</body>
</html>
