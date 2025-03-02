<?php
require_once '../config/config.php';
require_once '../templates/header.php';
require_once '../templates/navbar.php';

$query = "SELECT id,make,model, price, image_url FROM cars WHERE active = 1 ";
$result = mysqli_query($conn, $query);
?>

<div class="container mx-auto mt-6">
    <h1 class="text-3xl font-bold text-center">🚗 Danh Sách Xe</h1>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p class="text-center text-gray-500 mt-4">Không có xe nào trong hệ thống.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="border rounded-lg p-4 shadow-lg text-center">
                    <img src="../<?php echo $row['image_url']; ?>" alt="<?php echo $row['make'] . ' ' . $row['model']; ?>" class="w-full h-48 object-cover rounded-lg">
                    <h2 class="text-xl font-bold mt-2"><?php echo $row['make'] . ' ' . $row['model']; ?></h2>
                    <p class="text-lg font-semibold text-red-600">$<?php echo number_format($row['price'], 2); ?></p>

                    <form action="add_to_basket.php" method="POST" class="mt-2">
                        <input type="hidden" name="car_id" value="<?= $row['id']; ?>">
                        <button type="submit" class="block w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">
                            🛒 Múc ngay và luôn
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../templates/footer.php'; ?>