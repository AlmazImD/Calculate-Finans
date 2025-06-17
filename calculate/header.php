<?php
require_once 'config.php';
?>

<header>
    <div class="container">
        <div class="header-content">
            <h1><a href="dashboard.php">Финансовый калькулятор</a></h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Главная</a></li>
                    <li><a href="add_transaction.php">Добавить транзакцию</a></li>
                    <li><a href="logout.php">Выйти</a></li>
                </ul>
            </nav>
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
        </div>
    </div>
</header>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>