<?php
require 'db.php';
session_start();

header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Вы не авторизованы']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Обработка GET-запроса (получение карты)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM loyalty_cards WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $card = $stmt->fetch();

        if ($card) {
            echo json_encode(['status' => 'exists', 'card' => $card]);
        } else {
            echo json_encode(['status' => 'no_card']);
        }
    } catch (PDOException $e) {
        error_log("Ошибка получения карты: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка сервера']);
    }
    exit;
}

// Обработка POST-запроса (создание карты)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF (если используется на сайте)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный CSRF токен']);
        exit;
    }

    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Имя обязательно']);
        exit;
    }

    try {
        // Проверка наличия карты
        $stmt = $pdo->prepare("SELECT id FROM loyalty_cards WHERE user_id = ?");
        $stmt->execute([$user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'exists']);
            exit;
        }

        // Генерация номера карты
        $card_number = 'AQUA-' . strtoupper(bin2hex(random_bytes(4)));

        // Вставка карты
        $stmt = $pdo->prepare("
            INSERT INTO loyalty_cards 
            (user_id, name, card_number, balance, first_purchase, discount_rate, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $name, $card_number, 200.00, 0, 0.15]);

        echo json_encode(['status' => 'ok', 'card_number' => $card_number]);

    } catch (PDOException $e) {
        error_log("Ошибка создания карты: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка при создании карты']);
    }

    exit;
}

// Если неизвестный метод запроса
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Метод не поддерживается']);
