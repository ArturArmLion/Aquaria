<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false, 'message'=>'Не авторизован']);
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    echo json_encode(['success'=>false, 'message'=>'Доступ запрещён']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_user':
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if (!$username || !$email || !$password) {
            echo json_encode(['success'=>false, 'message'=>'Все поля обязательны']);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success'=>false, 'message'=>'Неверный email']);
            exit();
        }

        // Проверка на существование email
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            echo json_encode(['success'=>false, 'message'=>'Email уже зарегистрирован']);
            exit();
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hash, $role]);

        echo json_encode(['success'=>true, 'message'=>'Пользователь добавлен']);
        break;

    case 'edit_user':
        $id = $_POST['user_id'] ?? 0;
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';

        if (!$id || !$username || !$email || !$role) {
            echo json_encode(['success'=>false, 'message'=>'Все поля обязательны']);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success'=>false, 'message'=>'Неверный email']);
            exit();
        }

        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $email, $role, $id]);

        echo json_encode(['success'=>true, 'message'=>'Пользователь обновлён']);
        break;

    case 'delete_user':
        $id = $_POST['user_id'] ?? 0;
        if (!$id) {
            echo json_encode(['success'=>false, 'message'=>'ID обязателен']);
            exit();
        }
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success'=>true, 'message'=>'Пользователь удалён']);
        break;

    case 'add_product':
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $description = $_POST['description'] ?? '';

        if (!$name || $price === '') {
            echo json_encode(['success'=>false, 'message'=>'Имя и цена обязательны']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $description]);

        echo json_encode(['success'=>true, 'message'=>'Товар добавлен']);
        break;

    case 'edit_product':
        $id = $_POST['product_id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $description = $_POST['description'] ?? '';

        if (!$id || !$name || $price === '') {
            echo json_encode(['success'=>false, 'message'=>'Все поля обязательны']);
            exit();
        }

        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $id]);

        echo json_encode(['success'=>true, 'message'=>'Товар обновлён']);
        break;

    case 'delete_product':
        $id = $_POST['product_id'] ?? 0;
        if (!$id) {
            echo json_encode(['success'=>false, 'message'=>'ID обязателен']);
            exit();
        }
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success'=>true, 'message'=>'Товар удалён']);
        break;

    default:
        echo json_encode(['success'=>false, 'message'=>'Неизвестное действие']);
}
