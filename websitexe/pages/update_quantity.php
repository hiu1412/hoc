<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['car_id']) && isset($_POST['action'])) {
    $car_id = $_POST['car_id'];
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'] ?? null;
    $session_id = session_id();

    //lay so luong hien tai
    if ($user_id) {
        $query_get_quantity = "SELECT quantity FROM basket WHERE car_id = ? AND user_id = ?";
        $stmt_get_quantity = mysqli_prepare($conn, $query_get_quantity);
        mysqli_stmt_bind_param($stmt_get_quantity, "ii", $car_id, $user_id);
    } else {
        $query_get_quantity = "SELECT quantity FROM basket WHERE car_id = ? AND session_id = ?";
        $stmt_get_quantity = mysqli_prepare($conn, $query_get_quantity);
        mysqli_stmt_bind_param($stmt_get_quantity, "is", $car_id, $session_id);
    }

    mysqli_stmt_execute($stmt_get_quantity);
    mysqli_stmt_bind_result($stmt_get_quantity, $current_quantity);
    mysqli_stmt_fetch($stmt_get_quantity);
    mysqli_stmt_close($stmt_get_quantity);

    //tính số lượng mới
    if ($action === 'increase') {
        $quantity = $current_quantity + 1;
    } elseif ($action === 'decrease' && $current_quantity > 1) {
        $quantity = $current_quantity - 1;
    } else {
        $quantity = $current_quantity;
    }

    //cap nhat so luongluong
    if ($user_id) {
        $query = "UPDATE basket SET quantity = ? WHERE car_id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $car_id, $user_id);
    } else {
        $query = "UPDATE basket SET quantity = ? WHERE car_id = ? AND session_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iis", $quantity, $car_id, $session_id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: basket.php');
    exit();
}
