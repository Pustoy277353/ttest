<?php
include(__DIR__ . '/../src/conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'operator') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $stmt = $conn->prepare('UPDATE contact_submissions SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $_POST['status'], $_POST['id']);
    $stmt->execute();
    $stmt->close();
}

$submissions = [];
$result = $conn->query('SELECT * FROM contact_submissions');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
    $result->free();
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель Оператора</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <header>
        <h1>СтройДом</h1>
        <a href="logout.php">Выход</a>
    </header>

    <h2>Список отправленных форм</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Сообщение</th>
                <th>Дата отправки</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $submission): ?>
                <tr>
                    <td><?= htmlspecialchars($submission['id']) ?></td>
                    <td><?= htmlspecialchars($submission['name']) ?></td>
                    <td><?= htmlspecialchars($submission['email']) ?></td>
                    <td><?= htmlspecialchars($submission['message']) ?></td>
                    <td><?= htmlspecialchars($submission['submitted_at']) ?></td>
                    <td><?= htmlspecialchars($submission['status']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($submission['id']) ?>">
                            <select name="status">
                                <option value="новое" <?= $submission['status'] == 'новое' ? 'selected' : '' ?>>Новое</option>
                                <option value="одобренно" <?= $submission['status'] == 'одобренно' ? 'selected' : '' ?>>Одобренно</option>
                                <option value="завершено" <?= $submission['status'] == 'завершено' ? 'selected' : '' ?>>Завершено</option>
                                <option value="отклонено" <?= $submission['status'] == 'отклонено' ? 'selected' : '' ?>>Отклонено</option>
                            </select>
                            <button type="submit" name="update_status">Обновить статус</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
