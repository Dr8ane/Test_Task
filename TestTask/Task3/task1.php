<?php
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

// Подключение файла HTML
include 'task1.html';
?>
