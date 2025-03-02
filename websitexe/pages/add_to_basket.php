<?php
session_start();
require_once '../config/config.php';

// Kiểm tra kết nối CSDL
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Lỗi kết nối cơ sở dữ liệu!"]));
}

// Lấy ID xe từ request
$car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;

// Nếu không có ID hợp lệ
if (!$car_id) {
    echo json_encode(["status" => "error", "message" => "🚨 Xe không hợp lệ!"]);
    exit();
}

// Kiểm tra nếu user đã đăng nhập
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Kiểm tra xem xe đã có trong giỏ hàng chưa
    $query_check = "SELECT quantity FROM basket WHERE user_id = ? AND car_id = ?";
    $stmt_check = mysqli_prepare($conn, $query_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $car_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    $query = (mysqli_stmt_num_rows($stmt_check) > 0) ?
        "UPDATE basket SET quantity = quantity + 1 WHERE user_id = ? AND car_id = ?" :
        "INSERT INTO basket (user_id, car_id, quantity) VALUES (?, ?, 1)";

    mysqli_stmt_close($stmt_check);

    // Chuẩn bị truy vấn
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $car_id);
} else {
    // Người dùng chưa đăng nhập, dùng session_id
    $session_id = session_id();

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $query_check = "SELECT quantity FROM basket WHERE session_id = ? AND car_id = ?";
    $stmt_check = mysqli_prepare($conn, $query_check);
    mysqli_stmt_bind_param($stmt_check, "si", $session_id, $car_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    $query = (mysqli_stmt_num_rows($stmt_check) > 0) ?
        "UPDATE basket SET quantity = quantity + 1 WHERE session_id = ? AND car_id = ?" :
        "INSERT INTO basket (session_id, car_id, quantity) VALUES (?, ?, 1)";

    mysqli_stmt_close($stmt_check);

    // Chuẩn bị truy vấn
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $session_id, $car_id);
}

// Thực thi truy vấn
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success", "message" => "🚗 Xe đã được thêm vào giỏ hàng!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi thêm xe vào giỏ hàng: " . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit();
