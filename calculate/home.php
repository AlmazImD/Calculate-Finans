<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<title>Калькулятор бюджета</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<h2>Калькулятор финансового бюджета</h2>
<p>Здравствуйте, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">Выйти</a></p>

<div class="calculator">
    <label>Доходы:</label>
    <input type="number" id="income" placeholder="Введите доходы" /><br><br>
    <label>Расходы:</label>
    <input type="number" id="expenses" placeholder="Введите расходы" /><br><br>
    <button onclick="calculateBudget()">Рассчитать</button>
    <h3>Итог: <span id="result">0</span></h3>
</div>

<script src="js/calculator.js"></script>
</body>
</html>