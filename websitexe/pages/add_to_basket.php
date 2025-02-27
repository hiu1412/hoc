<?php
session_start();
require_once '../config/config.php';

// Kiểm tra kết nối database
if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Kiểm tra dữ liệu gửi lên
if (!isset($_POST['id']) || !intval($_POST['id'])) {
    $_SESSION['error'] = "Xe không hợp lệ!";
    header("Location: index.php");
    exit();
}

$car_id = intval($_POST['id']);

// Kiểm tra đăng nhập
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO basket (user_id, car_id, quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $car_id);
} else {
    $session_id = session_id();
    $query = "INSERT INTO basket (session_id, car_id, quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $session_id, $car_id);
}

// Kiểm tra lỗi SQL
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "🚗 Xe đã được thêm vào giỏ hàng!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi thêm xe vào giỏ hàng! " . mysqli_error($conn)]);
}

// Đóng kết nối
mysqli_stmt_close($stmt);
mysqli_close($conn);
exit();
