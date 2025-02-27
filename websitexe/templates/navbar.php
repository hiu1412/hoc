<?php session_start(); ?>
<?php require_once '../config/config.php'; ?>

<?php
$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();
$cart_count = 0;

//dem so luong gio hang
if ($user_id) {
    $query = "SELECT SUM(quantity) AS total FROM basket WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
} else {
    $query = "SELECT SUM(quantity) AS total FROM basket WHERE session_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$cart_count = $row['total'] ?? 0;
?>

<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-white text-xl font-bold">🏎️ Chơi Xe Đừng Để Xe Chơi</a>
        <div class="space-x-4 flex items-center">
            <!-- Giỏ hàng -->
            <a href="basket.php" class="relative text-white">
                🛒
                <?php if ($cart_count > 0): ?>
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        <?= $cart_count ?>
                    </span>
                <?php endif; ?>
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-white">👤 <?= htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Đăng xuất</a>
            <?php else: ?>
                <a href="register.php" class="bg-blue-500 text-white px-4 py-2 rounded">Đăng ký</a>
                <a href="login.php" class="bg-green-500 text-white px-4 py-2 rounded">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </div>
</nav>