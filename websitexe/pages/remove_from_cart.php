<?php
session_start();
require_once '../config/config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['car_id'])); {
    $car_id = intval($_POST['car_id']);
    $user_id = $_SESSION['user_id'] ?? null;
    $session_id = session_id();

    if ($user_id) {
        $query = "DELETE FROM basket WHERE user_id = ? AND car_id =?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $car_id);
    } else {
        $query = "DELETE FROM basket WHERE session_id = ? AND car_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $session_id, $car_id);
    }
}
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success_message'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
} else {
    $_SESSION['error_message'] = "Không xóa được" . mysqli_stmt_error($stmt);
}
mysqli_stmt_close($stmt);

header("Location: basket.php");
exit();
