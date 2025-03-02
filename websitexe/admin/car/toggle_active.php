<?php
require_once './../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Chuyển ID thành số nguyên

    // Lấy trạng thái hiện tại của xe
    $sql = "SELECT active FROM cars WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newStatus = $row['active'] ? 0 : 1; // Đảo trạng thái

        // Cập nhật trạng thái
        $updateSql = "UPDATE cars SET active = $newStatus WHERE id = $id";
        if ($conn->query($updateSql) === TRUE) {
            echo "<script>location.reload();</script>"; // Load lại trang ngay lập tức
        } else {
            echo "Lỗi khi cập nhật trạng thái!";
        }
    } else {
        echo "Xe không tồn tại!";
    }
} else {
    echo "Thiếu dữ liệu!";
}
