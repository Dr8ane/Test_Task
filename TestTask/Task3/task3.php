<?php
// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=test_tusk', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получение данных из базы данных для отображения
$stmt = $pdo->query("SELECT name_news, text_news, date_publication FROM news ORDER BY date_publication DESC LIMIT 3");
$news_ = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Подключение файла HTML
include 'task3.html';
?>
