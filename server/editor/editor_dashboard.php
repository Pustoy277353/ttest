<?php
include(__DIR__ . '/../src/conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'editor') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_house'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));

    $stmt = $conn->prepare("INSERT INTO houses (category_id, title, description, image, price) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Ошибка подготовки запроса: ' . $conn->error);
    }

    $stmt->bind_param('issss', $category_id, $title, $description, $imgContent, $price);
    if ($stmt->execute() === TRUE) {
        echo "Новая запись успешно создана";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare('DELETE FROM houses WHERE id = ?');
    if ($stmt === false) {
        die('Ошибка подготовки запроса: ' . $conn->error);
    }

    $stmt->bind_param('i', $_GET['delete']);
    if ($stmt->execute() === TRUE) {
        echo "Запись успешно удалена";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

$result = $conn->query('SELECT id, category_id, title, description, price, TO_BASE64(image) as image FROM houses');
if ($result === false) {
    die('Ошибка выполнения запроса: ' . $conn->error);
}
$houses = $result->fetch_all(MYSQLI_ASSOC);

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
    <title>Панель Редактора</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>

<body>
    <header>
        <h1>СтройДом</h1>
        <a href="logout.php">Выход</a>
    </header>

    <h2>Добавить дом</h2>
    <form method="post" enctype="multipart/form-data">
        <select name="category_id" required>
            <option value="">Выберите категорию</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="title" placeholder="Название дома" required>
        <textarea name="description" placeholder="Площадь:
Количество спален:
Габариты (ШхГ):" required></textarea>
        <input type="file" name="image" required>
        <input type="number" name="price" placeholder="Цена" required>
        <button type="submit" name="add_house">Добавить дом</button>
    </form>

    <h2>Список домов</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Категория</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Изображение</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($houses as $house) : ?>
                <tr>
                    <?php
                    $category_name = '';
                    foreach ($categories as $category) {
                        if ($category['id'] == $house['category_id']) {
                            $category_name = $category['name'];
                            break;
                        }
                    }
                    ?>
                    <td><?= htmlspecialchars($category_name) ?></td>
                    <td><?= htmlspecialchars($house['title']) ?></td>
                    <td><?= htmlspecialchars($house['description']) ?></td>
                    <td><img src="data:image/jpeg;base64,<?= htmlspecialchars($house['image']) ?>" width="100"></td>
                    <td><?= htmlspecialchars($house['price']) ?></td>
                    <td>
                        <a href="editor/editor_edit_house.php?id=<?= htmlspecialchars($house['id']) ?>">Редактировать</a>
                        <a href="?delete=<?= htmlspecialchars($house['id']) ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>