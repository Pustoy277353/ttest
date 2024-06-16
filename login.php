<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
include 'server/src/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Ошибка запроса: " . $conn->error);
    }

    $user = mysqli_fetch_assoc($result);

    if ($password == $user["password"]) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION['user_role'] = $user['role'];
        header('Location: server/admin.php');
        exit();
    } else {
        echo "Неправильный логин или пароль.";
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="src/styles.css">
    <title>Вход</title>
</head>
<body>
    <header>
        <h1>СтройДом</h1>
    </header>
    <form method="post">
        <input type="text" name="username" placeholder="Имя пользователя" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
