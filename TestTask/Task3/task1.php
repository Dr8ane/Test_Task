<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$errors = [];
$name = $address = $phone = $email = ''; // Инициализируем переменные

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
        $addressPattern2 = "/^г\.\s*[\p{Cyrillic}]+,\s*к\.\d+$/u";
        //$addressPattern = "/^г\.\s*Зеленоград,\s*к\.\d+$/";


        
        $phonePattern = "/^\+7\d{10}$/";

        if (empty($name)) {
            $errors['name'] = 'Имя не может быть пустым.';
        }
        // Проверка адреса по обоим паттернам
        if (empty($address) || !(preg_match($addressPattern, $address)) && !preg_match($addressPattern2, $address)) {
            $errors['address'] = 'Адрес некорректен.';
        }
        if (empty($phone) || !preg_match($phonePattern, $phone)) {
            $errors['phone'] = 'Телефон некорректен.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email некорректен.';
        }

        if (empty($errors)) {
            // Подготовка и выполнение запроса
            $stmt = $pdo->prepare("INSERT INTO feedback (name, address, phone, email) VALUES (:name, :address, :phone, :email)");
            $stmt->execute([
                ':name' => $name,
                ':address' => $address,
                ':phone' => $phone,
                ':email' => $email,
            ]);
            // Сбрасываем поля формы после успешной отправки
            $name = $address = $phone = $email = ''; 
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
    <link rel="stylesheet" href="stylesheettask.css">
    <title>Форма обратной связи</title>
    <style>
        input.incorrect {
            border: 2px solid red;
        }
        input.correct {
            border: 2px solid green;
        }
    </style>
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
                <input type="text" name="name" placeholder="Ваше имя" value="<?php echo htmlspecialchars($name); ?>" class="<?php echo isset($errors['name']) ? 'incorrect' : (empty($errors) && !empty($name) ? 'correct' : ''); ?>" required>
                <span><?php echo $errors['name'] ?? ''; ?></span>

                <input type="text" name="address" placeholder="г.Зеленоград, к.357" value="<?php echo htmlspecialchars($address); ?>" class="<?php echo isset($errors['address']) ? 'incorrect' : (empty($errors) && !empty($address) ? 'correct' : ''); ?>" required>
                <span><?php echo $errors['address'] ?? ''; ?></span>

                <input type="text" name="phone" placeholder="+7XXXXXXXXXX" value="<?php echo htmlspecialchars($phone); ?>" class="<?php echo isset($errors['phone']) ? 'incorrect' : (empty($errors) && !empty($phone) ? 'correct' : ''); ?>" required>
                <span><?php echo $errors['phone'] ?? ''; ?></span>

                <input type="email" name="email" placeholder="example@example.com" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo isset($errors['email']) ? 'incorrect' : (empty($errors) && !empty($email) ? 'correct' : ''); ?>" required>
                <span><?php echo $errors['email'] ?? ''; ?></span>

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
