<?php
session_start();
require_once '../config/config.php';
//trim dung de xoa khoang trang
if ($_SERVER["REQUEST_METHOD"] == "POST") { //kiem tra xem co gui yeu cau den server khong, neu co thi thuc thi
    //lay du lieu username,email,password,conffirm_password nguoi dung nhap
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    //kiem tra mat khau
    if ($password != $confirm_password) {
        $_SESSION['error'] = "Mat khau nhap lai khong khop"; //luu thong bao loi vao session
        header("Location: register.php"); //chuyen huong ve register.php lai
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Mat khau phai co it nhat 6 ky tu";
        header("Location: register.php");
        exit();
    }

    //Đoạn mã này thực hiện các bước sau:

    // Chuẩn bị một câu lệnh SQL với các placeholder.
    // Liên kết giá trị của $email với placeholder.
    // Thực thi câu lệnh đã chuẩn bị.
    // Lưu trữ kết quả truy vấn trong bộ nhớ PHP.

    //placeholder: dấu giữ chổ, ví dụ thay vì username = "hieu" thì username sẽ là "?", bảo mật tránh khỏi SQL injection
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); //password_default duoc dung de chi dinh ham bam

    $role_id = 1; //user
    //KIEM TRA EMAIL TON TAI CHUA
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email); //dùng để gán giá trị vào dấu "?"
    mysqli_stmt_execute($stmt); //thực hiện truy vấn giá trị vừa gán vào "?"
    mysqli_stmt_store_result($stmt); //luu ket qua va kiem tra xem co ban ghi nao khongkhong

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $SESSION['error'] = "Email da ton tai";
        header("Location: register.php");
        exit();
    }

    //them nguoi dung mới vao database
    $query = "INSERT INTO users(username,email, password ,role_id) VALUES(?,?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $hashed_password, $role_id); //sss la username ->string, email -> sting, hashed_password -> string, role_id -> intint

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['succes'] = "Đăng ký thành công! mời bạn đăng nhập";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Lỗi rồi đăng ký lại đi";
    }
}
?>

<?php require_once '../templates/header.php'; ?>

<div class="container mx-auto mt-10 max-w-md">
    <h2 class="text-2xl font-bold text-center">Đăng Ký</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="text-red-500"><?= $_SESSION['error'];
                                unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST" class="mt-4 space-y-4">
        <input type="text" name="username" placeholder="Ten dang nhap" required class="border p-2 w-full">
        <input type="email" name="email" placeholder="Email" required class="border p-2 w-full">
        <input type="password" name="password" placeholder="Mật khẩu" required class="border p-2 w-full">
        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required class="border p-2 w-full">

        <button type="submit" class="bg-blue-500 text-white p-2 w-full">Đăng Ký</button>
    </form>

    <p class="mt-4 text-center">Đã có tài khoản? <a href="login.php" class="text-blue-500"> Đăng nhập</a> </p>
</div>
<?php require_once '../templates/footer.php'; ?>