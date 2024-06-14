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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    $image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));

    $sql = "INSERT INTO houses (category_id, title, description, image, price)
            VALUES ('$category_id', '$title', '$description', '$imgContent', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
