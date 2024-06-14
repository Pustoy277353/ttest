<?php
include 'conn.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $stmt = $conn->prepare("SELECT id, title, description, price, TO_BASE64(image) as image FROM houses WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $houses = array();
    
    while ($row = $result->fetch_assoc()) {
        $houses[] = $row;
    }
    
    $stmt->close();
} else {
    die("Invalid category_id");
}

$conn->close();
echo json_encode($houses);

