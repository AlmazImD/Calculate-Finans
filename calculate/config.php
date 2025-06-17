<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'budget_calculator');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories = [
    'income' => ['Зарплата', 'Фриланс', 'Инвестиции', 'Подарки', 'Другое'],
    'expense' => ['Еда', 'Транспорт', 'Жилье', 'Развлечения', 'Одежда', 'Здоровье', 'Образование', 'Другое']
];
?>