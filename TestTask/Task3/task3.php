<?php
// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=test_tusk', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получение данных из базы данных для отображения
$stmt = $pdo->query("SELECT name_news, text_news, date_publication FROM news ORDER BY date_publication DESC LIMIT 3");
$news_ = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Подключение файла HTML

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styletask.css">
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
    <section class="table_content">
        <h2>Последние новости:</h2>
        <div class="container">
            <!-- Вывод данных под формой -->

            <table>
                <tr>
                    <th>Название</th>
                    <th>Текст</th>
                    <th>Дата публикации</th>
                </tr>
                <?php foreach ($news_ as $news):
    preg_match('/^(.*?[.!?])/', $news["text_news"], $matches);
    $firstSentence = $matches[0] ?? '';?>
                <tr>
                    <td><?php echo htmlspecialchars($news['name_news']); ?></td>
                    <td><?php echo htmlspecialchars($firstSentence); ?></td>
                    <td><?php echo htmlspecialchars($news['date_publication']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</main>
</body>
</html>
