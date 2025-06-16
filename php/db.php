<?php
$host = 'localhost';      // имя сервера
$db   = 'Aquaria';        // имя базы данных
$user = 'root';           // логин (по умолчанию root)
$pass = '';               // пароль (по умолчанию пустой)
$charset = 'utf8mb4';     // кодировка

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     echo 'Ошибка подключения: ' . $e->getMessage();
}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
