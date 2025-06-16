<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$isAdmin = ($user['role'] === 'admin');

// Создаем CSRF токен, если его нет в сессии
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Загрузка нового фото
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Неверный CSRF токен.");
    }
    $targetDir = "uploads/";
    $filename = basename($_FILES["profile_picture"]["name"]);
    $targetFilePath = $targetDir . $filename;

    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath);
    $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?")->execute([$targetFilePath, $user_id]);
    header("Location: user-profile.php");
    exit();
}

// Обновление профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Неверный CSRF токен.");
    }
    $username = $_POST['username'];
    $email = $_POST['email'];

    $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?")->execute([$username, $email, $user_id]);
    header("Location: user-profile.php");
    exit();
}

// Получение заказов пользователя
$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders->execute([$user_id]);
$orderList = $orders->fetchAll();

// Получение избранных товаров пользователя
$favStmt = $pdo->prepare("
    SELECT p.id, p.name, p.price, p.image 
    FROM favorites f
    JOIN products p ON f.product_id = p.id
    WHERE f.user_id = ?
");
$favStmt->execute([$user_id]);
$favorites = $favStmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="user-profile.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7/inputmask.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="profile-actions" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <a href="javascript:history.back()" class="back-button">← Назад</a>
            <a href="../backend/logout.php" class="logout-button">Выйти</a>
        </div>

        <h2>Добро пожаловать, <?= htmlspecialchars($user['username']) ?>!</h2>

        <section class="profile-section">
            <h3>Профиль</h3>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <img src="<?= $user['profile_image'] ?? 'default.png' ?>" width="100"><br>
                <input type="file" name="profile_picture"><br><br>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <button type="submit" name="update_profile">Сохранить</button>
            </form>
        </section>


        <!-- // HTML личного кабинета (вставить в кабинет пользователя) -->
        <div class="loyalty-card">
            <h3>Карта лояльности</h3>
            <div id="loyalty-content" class="card-wrapper">Загрузка...</div>
        </div>

        <section class="orders-section">
            <h3>Мои заказы</h3>
            <?php if (count($orderList) > 0): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Сумма (₽)</th>
                            <th>Скидка (₽)</th>
                            <th>Итоговая сумма (₽)</th>
                            <th>Адрес доставки</th>
                            <th>Метод оплаты</th>
                            <th>Статус</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderList as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= number_format($order['total_price'], 2, '.', '') ?> ₽</td>
                                <td><?= number_format($order['discount'], 2, '.', '') ?> ₽</td>
                                <td><?= number_format($order['final_price'], 2, '.', '') ?> ₽</td>
                                <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td><?= htmlspecialchars($order['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Вы пока не сделали ни одного заказа.</p>
            <?php endif; ?>
        </section>

        <section class="favorites-section">
            <h3>Избранные товары</h3>

            <?php if (count($favorites) > 0): ?>
                <div class="favorites-grid">
                    <?php foreach ($favorites as $product): ?>
                        <div class="favorite-item">
                            <img src="../images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100">
                            <p><?= htmlspecialchars($product['name']) ?></p>
                            <p><strong><?= number_format($product['price'], 2) ?> ₽</strong></p>
                            <form method="post" action="remove_favorite.php">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit">Удалить из избранного</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>У вас пока нет избранных товаров.</p>
            <?php endif; ?>
        </section>
        
        <?php if ($isAdmin): ?>
            <a href="admin-panel.php" class="admin-panel-link" style="display:inline-block; margin-top: 10px; padding: 8px 15px; background:#333; color:#fff; border-radius:4px; text-decoration:none;">
                Админ-панель
            </a>
        <?php endif; ?>



    <section class="support-section">
        <h3>Обращение в поддержку</h3>
        <form id="support-form" method="post">
            <textarea name="message" id="support-message" required placeholder="Опишите вашу проблему..."></textarea><br>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit">Отправить</button>
        </form>
        <div id="response-message" style="margin-top: 10px;"></div>
    </section>
    </div>

    <!-- СКРИПТ ДЛЯ ОТПРАВКИ ОБРАЩЕНИЯ -->

    <script>
        // Обработчик формы отправки обращения в поддержку
        document.getElementById('support-form').addEventListener('submit', function(e) {
            e.preventDefault();  // Отменяем стандартное поведение формы (перезагрузку страницы)

            // Получаем данные формы
            const formData = new FormData(this);

            // Отправляем запрос на сервер
            fetch('../php/submit_support_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const responseMessage = document.getElementById('response-message');
                if (data.status === 'success') {
                    responseMessage.innerHTML = '<p style="color: green;">' + data.message + '</p>';
                    document.getElementById('support-message').value = ''; // Очищаем текстовое поле
                } else {
                    responseMessage.innerHTML = '<p style="color: red;">' + data.message + '</p>';
                }
            })
            .catch(error => {
                console.error('Ошибка при отправке запроса:', error);
                document.getElementById('response-message').innerHTML = '<p style="color: red;">Произошла ошибка. Попробуйте еще раз.</p>';
            });
        });
    </script>


        <!-- КАРТА ЛОЯЛЬНОСТИ -->

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('loyalty-content');

                fetch('../php/loyalty_card.php')
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'exists') {
                            const card = data.card;
                            container.innerHTML = `
                                <div class="loyalty-card-box">
                                    <h4>Карта клиента</h4>
                                    <div>
                                        <div class="card-number">${card.card_number}</div>
                                        <div class="card-balance">Баланс: ${parseFloat(card.balance).toFixed(2)} ₽</div>
                                    </div>
                                    <div class="card-footer">
                                        Владелец: ${card.name}<br>
                                        Первая покупка: ${card.first_purchase == 1 ? 'Совершена' : 'Нет'}
                                    </div>
                                </div>
                            `;
                        } else if (data.status === 'no_card') {
                            container.innerHTML = `
                                <div>
                                    <p>У вас пока нет карты лояльности.</p>
                                    <button id="create-loyalty-card">Получить карту</button>
                                </div>
                            `;
                            document.getElementById('create-loyalty-card').addEventListener('click', () => {
                                const name = prompt("Введите имя для вашей карты:");
                                if (!name) return;

                                const formData = new FormData();
                                const csrfToken = '<?= $_SESSION['csrf_token'] ?>'; // вставляем из PHP 
                                formData.append('name', name);
                                formData.append('csrf_token', csrfToken);

                        fetch('../php/loyalty_card.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(async res => {
                            const contentType = res.headers.get('content-type');
                            let result;

                            try {
                                result = contentType && contentType.includes('application/json')
                                    ? await res.json()
                                    : { status: 'error', message: 'Ответ не в формате JSON', raw: await res.text() };
                            } catch (err) {
                                console.error("Ошибка при парсинге JSON:", err);
                                throw err;
                            }

                            console.log('Ответ от loyalty_card.php:', result);

                            if (result.status === 'ok') {
                                alert("Карта успешно создана!");
                                location.reload();
                            } else if (result.status === 'exists') {
                                alert("Карта уже существует.");
                                location.reload();
                            } else {
                                alert("Ошибка: " + result.message);
                                if (result.raw) console.warn("Сырой ответ сервера:", result.raw);
                            }
                        })
                        .catch(err => {
                            console.error("Ошибка запроса:", err);
                            alert("Произошла ошибка при отправке запроса.");
                        });

                            });
                        } else {
                            container.innerHTML = '<p>Ошибка при загрузке карты. Повторите позже.</p>';
                        }
                    })
                    .catch(err => {
                        console.error("Ошибка загрузки карты:", err);
                        container.innerHTML = '<p>Сервер недоступен.</p>';
                    });
            });
        </script>


</body>
</html>
