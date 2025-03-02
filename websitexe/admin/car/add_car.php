<?php
require_once './../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST["make"];
    $model = $_POST["model"];
    $registration = $_POST["registration"];
    $engine_size = $_POST["engine_size"];
    $price = $_POST["price"];
    $image_url = $_POST["image_url"] ?? 'assets/images/cars/default.jpg';

    $image_url = 'assets/images/cars/default.jpg';

    $sql = "INSERT INTO cars (make, model, registration, engine_size, price, image_url) VALUES ('$make', '$model', '$registration', '$engine_size', '$price', '$image_url')";

    if ($conn->query($sql) === TRUE) {
        header("Location: cars.php");
    } else {
        echo "Lỗi" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Xe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="p-6 max-w-md mx-auto bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Thêm Xe</h1>
        <form action="add_car.php" method="POST">
            <input type="text" name="make" placeholder="Hãng xe" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="model" placeholder="Mẫu xe" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="registration" placeholder="Biển số" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="engine_size" placeholder="Dung tích động cơ" class="w-full p-2 border rounded mb-2" required>
            <input type="number" name="price" placeholder="Giá xe" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="image_url" placeholder="URL ảnh (tùy chọn)" class="w-full p-2 border rounded mb-2">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Thêm xe</button>
        </form>
    </div>
</body>

</html>