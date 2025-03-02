<?php
session_start();
require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    //kiem tra email co ton tai khong
    $query = "SELECT id,username,password FROM users WHERE email =?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
    mysqli_stmt_fetch($stmt);

    //neu user ton tai va mat khau dung 
    if ($id && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Dang nhap thanh cong!";

        // Cập nhật giỏ hàng từ session_id sang user_id
        $session_id = session_id();
        $query_update = "UPDATE basket SET user_id = ? WHERE session_id = ?";
        $stmt_update = mysqli_prepare($conn, $query_update);

        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, "is", $id, $session_id);
            if (!mysqli_stmt_execute($stmt_update)) {
                $_SESSION['error'] = "Lỗi khi cập nhật giỏ hàng: " . mysqli_stmt_error($stmt_update);
            }
            mysqli_stmt_close($stmt_update);
        } else {
            $_SESSION['error'] = "Lỗi khi chuẩn bị truy vấn cập nhật giỏ hàng: " . mysqli_error($conn);
        }

        // Sau khi cập nhật user_id, xóa session_id để tránh trùng lặp
        $query_clear_session = "UPDATE basket SET session_id = NULL WHERE user_id = ?";
        $stmt_clear = mysqli_prepare($conn, $query_clear_session);

        if ($stmt_clear) {
            mysqli_stmt_bind_param($stmt_clear, "i", $id);
            mysqli_stmt_execute($stmt_clear);
            mysqli_stmt_close($stmt_clear);
        }

        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Email hoac mat khau khong dung";
        header("Location: login.php");
        exit();
    }
}
?>

<?php require_once '../templates/header.php'; ?>
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold text-center">Đăng Nhập</h2>
    <form action="login.php" method="POST" class="max-w-md mx-auto mt-4">
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 mb-2">
        <input type="password" name="password" placeholder="Mật khẩu" required class="w-full border p-2 mb-2">
        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Đăng nhập</button>
    </form>
</div>
<?php require_once '../templates/footer.php'; ?>