<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['user_role'];

switch ($role) {
    case 'admin':
        include 'admin/admin_dashboard.php';
        break;
    case 'editor':
        include 'editor/editor_dashboard.php';
        break;
    case 'operator':
        include 'operator/operator_dashboard.php';
        break;
    default:
        echo "Неизвестная роль";
        session_destroy();
        exit();
}
?>
