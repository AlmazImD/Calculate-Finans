<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $income = floatval($_POST['income']);
    $expenses = floatval($_POST['expenses']);

    $stmt = $pdo->prepare("INSERT INTO calculations (user_id, income, expenses) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $income, $expenses]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>