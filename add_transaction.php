<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = (float)$_POST['amount'];
    $date = $_POST['date'];
    $description = trim($_POST['description']);

    // Валидация
    if (empty($type) || empty($category) || empty($amount) || empty($date)) {
        $error = "Пожалуйста, заполните все обязательные поля";
    } elseif ($amount <= 0) {
        $error = "Сумма должна быть больше нуля";
    } else {
        // Добавляем транзакцию
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, category, amount, description, date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdss", $user_id, $type, $category, $amount, $description, $date);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Транзакция успешно добавлена";
            redirect('dashboard.php');
        } else {
            $error = "Ошибка при добавлении транзакции. Пожалуйста, попробуйте позже.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить транзакцию | Финансовый калькулятор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="form-container">
            <h2>Добавить транзакцию</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="add_transaction.php" method="POST">
                <div class="form-group">
                    <label for="type">Тип</label>
                    <select id="type" name="type" required>
                        <option value="">Выберите тип</option>
                        <option value="income">Доход</option>
                        <option value="expense">Расход</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">Категория</label>
                    <select id="category" name="category" required>
                        <option value="">Выберите категорию</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Сумма (₽)</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="date">Дата</label>
                    <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Описание (необязательно)</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Добавить</button>
                <a href="dashboard.php" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script src="script.js"></script>
    <script>
        // Обновление категорий при изменении типа
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const categorySelect = document.getElementById('category');
            
            if (!type) {
                categorySelect.innerHTML = '<option value="">Выберите категорию</option>';
                return;
            }
            
            // Категории из PHP (передаем через data-атрибуты или AJAX)
            const categories = {
                income: <?php echo json_encode($categories['income']); ?>,
                expense: <?php echo json_encode($categories['expense']); ?>
            };
            
            let options = '<option value="">Выберите категорию</option>';
            categories[type].forEach(cat => {
                options += `<option value="${cat}">${cat}</option>`;
            });
            
            categorySelect.innerHTML = options;
        });
    </script>
</body>
</html>