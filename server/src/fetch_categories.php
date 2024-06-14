<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StroyDom";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->query("SET NAMES 'utf8'");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, TO_BASE64(image) as image FROM house_categories";
$result = $conn->query($sql);

$categories = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$conn->close();

echo json_encode($categories);
?>
