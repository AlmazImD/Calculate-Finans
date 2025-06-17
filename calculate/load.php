<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT income, expenses FROM calculations WHERE user_id = ? ORDER BY date DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();

    if ($result) {
        echo json_encode(['income' => $result['income'], 'expenses' => $result['expenses']]);
    } else {
        echo json_encode(['income' => 0, 'expenses' => 0]);
    }
}
?>