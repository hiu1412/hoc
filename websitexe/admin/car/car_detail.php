<?php
require_once './../../config/config.php';

if (!isset($_GET['id'])) {
    die("Thieu id xe");
}

$id = $_GET['id'];
$sql = "SELECT * FROM cars WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Xe khong ton tai.");
}

$car = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết Xe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Chi tiết Xe</h1>
        <div class="bg-white p-6 shadow-md rounded-lg">
            <img src="<?= $car['image_url'] ?>" alt="Car Image" class="w-64 h-64 object-cover rounded mb-4">
            <p><strong>Hãng:</strong> <?= $car['make'] ?></p>
            <p><strong>Mẫu:</strong> <?= $car['model'] ?></p>
            <p><strong>Biển số:</strong> <?= $car['registration'] ?></p>
            <p><strong>Dung tích:</strong> <?= $car['engine_size'] ?></p>
            <p><strong>Giá:</strong> <?= number_format($car['price']) ?> VNĐ</p>
            <div class="mt-4">
                <a href="edit_car.php?id=<?= $car['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded">Sửa</a>
                <a href="car.php" class="bg-gray-500 text-white px-4 py-2 rounded">Quay lại</a>
            </div>
        </div>
    </div>
</body>

</html>