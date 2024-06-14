<?php
include 'conn.php';

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
