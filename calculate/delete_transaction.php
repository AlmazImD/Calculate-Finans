<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('dashboard.php');
}

$transaction_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Проверяем, принадлежит ли транзакция текущему пользователю
$stmt = $conn->prepare("SELECT id FROM transactions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['error'] = "Транзакция не найдена или у вас нет прав на ее удаление";
    redirect('dashboard.php');
}

// Удаляем транзакцию
$stmt = $conn->prepare("DELETE FROM transactions WHERE id = ?");
$stmt->bind_param("i", $transaction_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Транзакция успешно удалена";
} else {
    $_SESSION['error'] = "Ошибка при удалении транзакции";
}

redirect('dashboard.php');
?>