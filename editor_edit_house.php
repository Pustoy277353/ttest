<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StroyDom";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->query("SET NAMES 'utf8'");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'editor') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die('ID дома не указан');
}

$house_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_house'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    $image = $_FILES['image']['tmp_name'];
    $imgContent = !empty($image) ? addslashes(file_get_contents($image)) : null;

    if ($imgContent) {
        $stmt = $conn->prepare("UPDATE houses SET category_id = ?, title = ?, description = ?, image = ?, price = ? WHERE id = ?");
        if ($stmt === false) {
            die('Ошибка подготовки запроса: ' . $conn->error);
        }
        $stmt->bind_param('issssi', $category_id, $title, $description, $imgContent, $price, $house_id);
    } else {
        $stmt = $conn->prepare("UPDATE houses SET category_id = ?, title = ?, description = ?, price = ? WHERE id = ?");
        if ($stmt === false) {
            die('Ошибка подготовки запроса: ' . $conn->error);
        }
        $stmt->bind_param('isssi', $category_id, $title, $description, $price, $house_id);
    }

    if ($stmt->execute() === TRUE) {
        echo "Запись успешно обновлена";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

$stmt = $conn->prepare('SELECT id, category_id, title, description, price, TO_BASE64(image) as image FROM houses WHERE id = ?');
if ($stmt === false) {
    die('Ошибка подготовки запроса: ' . $conn->error);
}
$stmt->bind_param('i', $house_id);
$stmt->execute();
$result = $stmt->get_result();
$house = $result->fetch_assoc();
if (!$house) {
    die('Дом не найден');
}
$stmt->close();

$categories_result = $conn->query('SELECT * FROM house_categories');
if ($categories_result === false) {
    die('Ошибка выполнения запроса: ' . $conn->error);
}
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать дом</title>
    <link rel="stylesheet" href="src/styles.css">
</head>
<body>
    <h1>Редактировать дом</h1>

    <form method="post" enctype="multipart/form-data">
        <select name="category_id" required>
            <option value="">Выберите категорию</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $house['category_id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="title" value="<?= htmlspecialchars($house['title']) ?>" placeholder="Название дома" required>
        <textarea name="description" placeholder="Описание" required><?= htmlspecialchars($house['description']) ?></textarea>
        <input type="file" name="image">
        <?php if ($house['image']): ?>
            <img src="data:image/jpeg;base64,<?= htmlspecialchars($house['image']) ?>" width="100">
        <?php endif; ?>
        <input type="number" name="price" value="<?= htmlspecialchars($house['price']) ?>" placeholder="Цена" required>
        <button type="submit" name="edit_house">Обновить дом</button>
    </form>

    <p><a href="editor_dashboard.php">Вернуться к списку домов</a></p>
</body>
</html>
