<?php
require '../php/db.php';
require '../vendor/autoload.php'; // Подключение PHPMailer через Composer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (!$email) $errors[] = "Неверный email.";
    if (strlen($password) < 6) $errors[] = "Пароль слишком короткий.";
    if ($password !== $confirm_password) $errors[] = "Пароли не совпадают.";

    // Проверка на существующий email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $errors[] = "Email уже зарегистрирован.";

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16)); // Генерация токена для подтверждения

        // Добавляем пользователя с is_confirmed=0 (не подтвержден)
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, token, is_confirmed) 
            VALUES (?, ?, ?, ?, 0)
        ");
        $stmt->execute([$username, $email, $hashed, $token]);

        // Отправка письма с подтверждением
        $mail = new PHPMailer(true);
        try {
            // Настройки SMTP (пример для Gmail, нужно подставить свои)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'isip_a.t.akopyan@mpt.ru';  // ваш email SMTP
            $mail->Password = 'idtw berr leqa hayl';   // ваш пароль SMTP или app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('isip_a.t.akopyan@mpt.ru', 'Aquaria');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Подтверждение регистрации';
            $confirmLink = "http://localhost/ДИПЛОМ/backend/confirm.php?token=$token"; // замените на ваш домен
            $mail->Body = "
                <h2>Здравствуйте, $username!</h2>
                <p>Спасибо за регистрацию на нашем сайте.</p>
                <p>Для подтверждения аккаунта перейдите по ссылке:</p>
                <a href='$confirmLink'>$confirmLink</a>
                <p>Если вы не регистрировались, просто проигнорируйте это письмо.</p>
            ";

            $mail->send();

            echo "<p>Регистрация прошла успешно! Проверьте вашу почту для подтверждения аккаунта.</p>";
            exit();

        } catch (Exception $e) {
            $errors[] = "Ошибка при отправке письма: " . $mail->ErrorInfo;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="back.css">
</head>
<body class="sea-theme">
<div class="form-container">
    <h2>Регистрация</h2>
    <?php if (!empty($errors)): ?>
        <div class="error-box"><?= implode("<br>", $errors) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Имя пользователя" required>
        <input type="email" name="email" placeholder="Email" required>
        <div class="password-wrapper">
            <input type="password" name="password" placeholder="Пароль" id="password" required>
            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                <img src="../images/eye.png" alt="Показать пароль">
            </button>
        </div>
        <input type="password" name="confirm_password" placeholder="Повторите пароль" required>
        <button class="button" type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const img = button.querySelector('img');
        if (input.type === 'password') {
            input.type = 'text';
            img.src = '../images/eye-off.png';
            img.alt = 'Скрыть пароль';
        } else {
            input.type = 'password';
            img.src = '../images/eye.png';
            img.alt = 'Показать пароль';
        }
    }
</script>
</body>
</html>
