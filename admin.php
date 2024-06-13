<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Стройдом36 - Мы строим дома вашей мечты. Узнайте больше о наших услугах и проектах.">
    <meta name="keywords" content="строительство, дома, Стройдом36, Воронеж">
    <title>Админка</title>
    <link rel="stylesheet" href="src/styles.css">
    <script src="fetch_houses.js" defer></script>
</head>
<body>
    <header>
        <h1><a href="index.html">СтройДом</a></h1>
        <h2>Панель администратора</h2>
        <button id="toggleFormButton">Добавить дом</button>
    </header>

    <form id="addHouseForm" action="src/add_house.php" method="post" enctype="multipart/form-data" style="display:none">
        <label for="category_id">ID категории:</label>
        <input type="number" id="category_id" name="category_id" required><br><br>
        
        <label for="title">Название:</label>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea><br><br>
        
        <label for="image">Изображение:</label>
        <input type="file" id="image" name="image" required><br><br>
        
        <label for="price">Цена:</label>
        <input type="text" id="price" name="price" required><br><br>

        <input type="submit" value="Сохранить">
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Сообщение</th>
                <th>Отправлено</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "StroyDom";

            $conn = new mysqli($servername, $username, $password, $dbname);
            $conn->query("SET NAMES 'utf8'");

            if ($conn->connect_error) {
                die("Ошибка подключения: " . $conn->connect_error);
            }

            $sql = "SELECT id, name, email, message, submitted_at, status FROM contact_submissions ORDER BY submitted_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["id"]."</td>
                            <td>".$row["name"]."</td>
                            <td>".$row["email"]."</td>
                            <td>".$row["message"]."</td>
                            <td>".$row["submitted_at"]."</td>
                            <td>
                                <form method='post' action='src/update_status.php'>
                                    <input type='hidden' name='id' value='".$row["id"]."'>
                                    <select name='status'>
                                        <option value='новое' ".($row["status"] == "новое" ? "selected" : "").">Новое</option>
                                        <option value='одобрено' ".($row["status"] == "одобрено" ? "selected" : "").">Одобрено</option>
                                        <option value='завершено' ".($row["status"] == "завершено" ? "selected" : "").">Завершено</option>
                                        <option value='отклонено' ".($row["status"] == "отклонено" ? "selected" : "").">Отклонено</option>
                                    </select>
                                    <input type='submit' value='Изменить'>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "0 результатов";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2024 Стройдом. Все права защищены.</p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleFormButton = document.getElementById('toggleFormButton');
        var addHouseForm = document.getElementById('addHouseForm');

        toggleFormButton.addEventListener('click', function() {
            if (addHouseForm.style.display === 'none' || addHouseForm.style.display === '') {
                addHouseForm.style.display = 'block';
            } else {
                addHouseForm.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>