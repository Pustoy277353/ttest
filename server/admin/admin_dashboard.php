<?php
include(__DIR__ . '/../src/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $password, $role);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM users WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$sql = "SELECT id, username, password, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ Панель</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <header>
        <h1>СтройДом</h1>
        <a href="logout.php">Выход</a>
    </header>
    <form method="post">
        <input type="text" name="username" placeholder="Имя пользователя" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <select name="role">
            <option value="admin">Администратор</option>
            <option value="editor">Редактор</option>
            <option value="operator">Оператор</option>
        </select>
        <button type="submit" name="add_user">Добавить пользователя</button>
    </form>
    <h2>Пользователи</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Пароль</th>
            <th>Роль</th>
            <th>Действия</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"]. "</td>
                        <td>" . $row["username"]. "</td>
                        <td>" . $row["password"]. "</td>
                        <td>" . $row["role"]. "</td>
                        <td><a href='?delete=" . $row["id"]. "'>Удалить</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
