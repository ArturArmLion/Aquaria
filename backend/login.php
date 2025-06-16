<?php
session_start();
require '../php/db.php'; // Убедитесь, что путь к базе данных верный

$errors = [];

// Пример защиты от слишком частых запросов
if (!isset($_SESSION['last_request_time']) || (time() - $_SESSION['last_request_time']) > 10) {
    $_SESSION['last_request_time'] = time(); // обновляем время последнего запроса
} else {
    $errors[] = "Вы слишком часто отправляете запросы. Попробуйте через несколько секунд.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка CSRF токена
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Неверный токен безопасности. Пожалуйста, обновите страницу.";
    } else {
        $username = trim($_POST["username"]);
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            $errors[] = "Пожалуйста, заполните все поля.";
        } else {
            // Получение пользователя по логину
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['is_confirmed'] == 0) {
                    // Если email не подтверждён
                    $errors[] = "Пожалуйста, подтвердите ваш email. Проверьте почту.";
                } else if (password_verify($password, $user['password'])) {
                    // Успешный вход
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["role"] = $user["role"];

                    // Редирект по роли
                    if ($user["role"] === "admin") {
                        header("Location: ../user_index.php");
                    } else {
                        header("Location: ../user_index.php");
                    }
                    exit();
                } else {
                    $errors[] = "Неверный логин или пароль.";
                }
            } else {
                $errors[] = "Неверный логин или пароль.";
            }
        }
    }
}

// Генерация нового CSRF токена
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'">
    <title>Вход в аккаунт</title>
    <link rel="stylesheet" href="back.css"> <!-- Подключаем морскую тему -->
</head>
<body class="sea-theme">
    <div class="form-container">
        <h2>Авторизация</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?= implode("<br>", array_map('htmlspecialchars', $errors)) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="text" name="username" placeholder="Логин" required>
            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Пароль" id="password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                    <img src="../images/eye.png" alt="Показать пароль">
                </button>
            </div>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button class="button" type="submit">Войти</button>
        </form>

        <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const img = button.querySelector('img');

            if (input.type === 'password') {
                input.type = 'text';
                img.src = '../images/eye-off.png'; // глаз с перекрестием
                img.alt = 'Скрыть пароль';
            } else {
                input.type = 'password';
                img.src = '../images/eye.png'; // обычный глаз
                img.alt = 'Показать пароль';
            }
        }
    </script>

</body>
</html>
