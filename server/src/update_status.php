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

$id = $_POST['id'];
$new_status = $_POST['status'];

$sql = "UPDATE contact_submissions SET status='$new_status' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Статус успешно обновлен";
} else {
    echo "Ошибка при обновлении статуса: " . $conn->error;
}

$conn->close();
?>
