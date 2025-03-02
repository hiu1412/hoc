<?php
require_once './../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM cars WHERE id = $id";

    if ($conn->query($sql) == TRUE) {
        header("Location: cars.php");
    } else {
        echo "Loi khi xoa xe: " . $conn->error;
    }
}
