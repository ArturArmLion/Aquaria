<?php
require 'db.php'; // подключение к базе данных через PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение и очистка данных формы
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    // Проверка заполненности
    if (!empty($name) && !empty($phone)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO feedback (name, phone) VALUES (:name, :phone)");
            $stmt->execute(['name' => $name, 'phone' => $phone]);

            // Перенаправление
            header("Location: ../service.php");
            exit;
        } catch (PDOException $e) {
            echo "Ошибка при сохранении: " . $e->getMessage();
        }
    } else {
        echo "Пожалуйста, заполните все поля.";
    }
}
?>

