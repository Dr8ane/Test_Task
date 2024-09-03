<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    // Подключение к базе данных
    $pdo = new PDO('mysql:host=evgens19.beget.tech;dbname=evgens19_test', 'evgens19_test', 'd8Jtpa%l');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Если форма отправлена
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $addressPattern = "/^г\.\s*[А-Яа-яёЁы\s]+,\s*ул\.\s*[А-Яа-яёЁы\s]+,\s*д\.\s*\d+,\s*кв\.\s*\d+$/";
        $phonePattern = "/^\+7\d{10}$/";

        if (!empty($name) &&
            !empty($address) &&
            !empty($phone) &&
            !empty($email) &&
            preg_match($addressPattern, $address) &&
            preg_match($phonePattern, $phone) &&
            filter_var($email, FILTER_VALIDATE_EMAIL)) {
    
            // Подготовка и выполнение запроса
            $stmt = $pdo->prepare("INSERT INTO feedback (name, address, phone, email) VALUES (:name, :address, :phone, :email)");
            $stmt->execute([
                ':name' => $name,
                ':address' => $address,
                ':phone' => $phone,
                ':email' => $email,
            ]);
        } else {
            echo "<script>alert('Пожалуйста, проверьте все поля и заполните их правильно.');</script>";
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
                <input type="text" name="address" placeholder="г.Москва, ул.Ленина, д.1, кв.10" required>
                <input type="text" name="phone" placeholder="+7XXXXXXXXXX" required>
                <input type="email" name="email" placeholder="example@example.com" required>
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
