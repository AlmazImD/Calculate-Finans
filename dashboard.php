<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Получаем текущего пользователя
$user_id = $_SESSION['user_id'];

// Получаем параметры фильтрации
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'all';
$filter_category = isset($_GET['category']) ? $_GET['category'] : 'all';
$filter_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Подготавливаем запрос для транзакций
$query = "SELECT * FROM transactions WHERE user_id = ?";
$params = [$user_id];
$types = "i";

// Добавляем фильтры
if ($filter_type !== 'all') {
    $query .= " AND type = ?";
    $params[] = $filter_type;
    $types .= "s";
}

if ($filter_category !== 'all') {
    $query .= " AND category = ?";
    $params[] = $filter_category;
    $types .= "s";
}

if ($filter_month) {
    $query .= " AND DATE_FORMAT(date, '%Y-%m') = ?";
    $params[] = $filter_month;
    $types .= "s";
}

$query .= " ORDER BY date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Получаем суммы доходов и расходов
$income_stmt = $conn->prepare("SELECT SUM(amount) as total FROM transactions WHERE user_id = ? AND type = 'income' AND DATE_FORMAT(date, '%Y-%m') = ?");
$income_stmt->bind_param("is", $user_id, $filter_month);
$income_stmt->execute();
$total_income = $income_stmt->get_result()->fetch_assoc()['total'] ?? 0;

$expense_stmt = $conn->prepare("SELECT SUM(amount) as total FROM transactions WHERE user_id = ? AND type = 'expense' AND DATE_FORMAT(date, '%Y-%m') = ?");
$expense_stmt->bind_param("is", $user_id, $filter_month);
$expense_stmt->execute();
$total_expense = $expense_stmt->get_result()->fetch_assoc()['total'] ?? 0;

$balance = $total_income - $total_expense;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления | Финансовый калькулятор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="dashboard">
            <div class="summary">
                <div class="summary-card income">
                    <h3>Доходы</h3>
                    <p><?php echo number_format($total_income, 2); ?> ₽</p>
                </div>
                <div class="summary-card expense">
                    <h3>Расходы</h3>
                    <p><?php echo number_format($total_expense, 2); ?> ₽</p>
                </div>
                <div class="summary-card balance">
                    <h3>Баланс</h3>
                    <p><?php echo number_format($balance, 2); ?> ₽</p>
                </div>
            </div>
            
            <div class="filters">
                <form id="filterForm" method="GET">
                    <div class="form-group">
                        <label for="type">Тип</label>
                        <select id="type" name="type">
                            <option value="all" <?php echo $filter_type === 'all' ? 'selected' : ''; ?>>Все</option>
                            <option value="income" <?php echo $filter_type === 'income' ? 'selected' : ''; ?>>Доходы</option>
                            <option value="expense" <?php echo $filter_type === 'expense' ? 'selected' : ''; ?>>Расходы</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Категория</label>
                        <select id="category" name="category">
                            <option value="all" <?php echo $filter_category === 'all' ? 'selected' : ''; ?>>Все</option>
                            <?php foreach ($categories['income'] as $category): ?>
                                <option value="<?php echo $category; ?>" <?php echo $filter_category === $category ? 'selected' : ''; ?>>
                                    <?php echo $category; ?> (доход)
                                </option>
                            <?php endforeach; ?>
                            <?php foreach ($categories['expense'] as $category): ?>
                                <option value="<?php echo $category; ?>" <?php echo $filter_category === $category ? 'selected' : ''; ?>>
                                    <?php echo $category; ?> (расход)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month">Месяц</label>
                        <input type="month" id="month" name="month" value="<?php echo $filter_month; ?>">
                    </div>
                    <button type="submit" class="btn btn-small">Применить</button>
                </form>
            </div>
            
            <div class="transactions">
                <div class="transactions-header">
                    <h2>История транзакций</h2>
                    <a href="add_transaction.php" class="btn btn-small">Добавить транзакцию</a>
                </div>
                
                <?php if (empty($transactions)): ?>
                    <p>Нет транзакций</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Тип</th>
                                <th>Категория</th>
                                <th>Описание</th>
                                <th>Сумма</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="<?php echo $transaction['type']; ?>-row">
                                    <td><?php echo date('d.m.Y', strtotime($transaction['date'])); ?></td>
                                    <td><?php echo $transaction['type'] === 'income' ? 'Доход' : 'Расход'; ?></td>
                                    <td><?php echo $transaction['category']; ?></td>
                                    <td><?php echo $transaction['description'] ?: '-'; ?></td>
                                    <td><?php echo number_format($transaction['amount'], 2); ?> ₽</td>
                                    <td>
                                        <a href="delete_transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Вы уверены?')">Удалить</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script src="script.js"></script>
</body>
</html>