<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
// Подключение к базе данных
    $pdo = new PDO('mysql:host=localhost;dbname=test_tusk', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Если форма отправлена
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['address']) && !empty($_POST['address']) &&
            isset($_POST['phone']) && !empty($_POST['phone']) &&
            isset($_POST['email']) && !empty($_POST['email'])) {
    
            // Подготовка и выполнение запроса
            $stmt = $pdo->prepare("INSERT INTO feedback (name, address, phone, email) VALUES (:name, :address, :phone, :email)");
            $stmt->execute([
                ':name' => $_POST['name'],
                ':address' => $_POST['address'],
                ':phone' => $_POST['phone'],
                ':email' => $_POST['email'],
            ]);
        } else {
            echo "<script>alert('Пожалуйста, заполните все поля.');</script>";
        }
    }
    
    // Получение данных из базы данных для отображения
    $stmt = $pdo->query("SELECT name, address, phone, email FROM feedback");
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    echo 'Ошибка подключения: ' . $e->getMessage();

}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="taskstyle.css">
    <title>Форма обратной связи</title>
</head>
<body>
<header>
    <nav>
        <ul class="menu">
            <div class="container">
                <li><a href="task3.php">Главная</a></li>
                <li><a href="task2.php">Новости</a></li>
                <li><a href="task1.php">Обратная связь</a></li>
            </div>
        </ul>
    </nav>
</header>
<main>
    <section class="wrapper">
        <div class="container_2">
            <!-- HTML Форма -->
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="text" name="address" placeholder="Ваш адрес" required>
                <input type="text" name="phone" placeholder="Ваш телефон" required>
                <input type="email" name="email" placeholder="Ваш email" required>
                <button type="submit">Отправить</button>
            </form>
        </div>
    </section>
    <section class="table_content">
        <div class="container">
            <table>
                <tr>
                    <th>Имя</th>
                    <th>Адрес</th>
                    <th>Телефон</th>
                    <th>Email</th>
                </tr>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['address']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['phone']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>


