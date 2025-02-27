<?php
session_start();
require_once '../config/config.php';

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();
$basket_items = [];
$total_price = 0;

//Lay du lieu gio hang
if ($user_id) {
    $query = "SELECT b.car_id, c.make,c.model,c.price,c.image_url, b.quantity 
        FROM basket b 
        JOIN cars c 
        ON b.car_id = c.id 
        WHERE b.user_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
} else {
    $query = "SELECT b.car_id, c.make, c.model, c.price, c.image_url, b.quantity 
              FROM basket b 
              JOIN cars c ON b.car_id = c.id 
              WHERE b.session_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $basket_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}
?>
<?php require_once '../templates/header.php'; ?>

<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold text-center">Giỏ Hàng</h2>

    <?php if (!empty($basket_items)) : ?>
        <table class="table-auto w-full mt-4 border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Hình ảnh</th>
                    <th class="border p-2">Xe</th>
                    <th class="border p-2">Giá</th>
                    <th class="border p-2">Số lượng</th>
                    <th class="border p-2">Tổng</th>
                    <th class="border p-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($basket_items as $item) : ?>
                    <tr>
                        <!-- Cột hình ảnh -->
                        <td class="border p-2">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Ảnh xe" class="w-24 h-16 object-cover">
                        </td>
                        <td class="border p-2"><?= htmlspecialchars($item['make'] . ' ' . $item['model']) ?></td>
                        <td class="border p-2"><?= number_format($item['price']) ?> VND</td>
                        <td class="border p-2">
                            <form action="update_quantity.php" method="POST" class="flex items-center space-x-2">
                                <input type="hidden" name="car_id" value="<?= $item['car_id'] ?>">
                                <button type="submit" name="action" value="decrease" class="bg-gray-300 px-2 py-1 rounded">-</button>
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="w-12 text-center border">
                                <button type="submit" name="action" value="increase" class="bg-gray-300 px-2 py-1 rounded">+</button>
                            </form>
                        </td>
                        <td class="border p-2"><?= number_format($item['price'] * $item['quantity']) ?> VND</td>
                        <td class="border p-2">
                            <form action="remove_from_cart.php" method="POST">
                                <input type="hidden" name="car_id" value="<?= $item['car_id'] ?>">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right mt-4">
            <h3 class="text-xl font-bold">Tổng cộng: <?= number_format($total_price) ?> VND</h3>
        </div>
    <?php else : ?>
        <p class="text-center mt-4 text-gray-500">Giỏ hàng trống</p>
    <?php endif; ?>
</div>
<?php require_once '../templates/footer.php'; ?>