<?php
session_start();

$conn = new mysqli("localhost", "root", "", "StroyDom");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

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
        header('Location: admin.php');
        exit();
    } else {
        echo "Неправильный логин или пароль.";
        echo $user["password"],'<br>';
        echo $password;
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
    <form method="post">
        <input type="text" name="username" placeholder="Имя пользователя" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
