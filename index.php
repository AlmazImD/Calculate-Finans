<?php
require_once 'auth.php';

if (isLoggedIn()) {
  redirect('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Финансовый калькулятор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h1>Добро пожаловать в Финансовый калькулятор</h1>
            <p>Управляйте своими финансами эффективно и просто</p>
            <div class="auth-buttons">
                <a href="login.php" class="btn">Войти</a>
                <a href="register.php" class="btn btn-secondary">Зарегистрироваться</a>
            </div>
        </div>
    </div>
</body>
</html>