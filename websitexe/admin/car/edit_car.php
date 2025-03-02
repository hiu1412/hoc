<?php

require_once './../../config/config.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID xe.");
}

$id = $_GET['id'];
$sql = "SELECT * FROM cars WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Xe không tồn tại.");
}

$car = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST["make"];
    $model = $_POST["model"];
    $registration = $_POST["registration"];
    $engine_size = $_POST["engine_size"];
    $price = $_POST["price"];
    $image_url = $_POST["image_url"] ?? 'assets/images/cars/default.jpg';

    $sql = "UPDATE cars SET 
            make = '$make', 
            model = '$model', 
            registration = '$registration', 
            engine_size = '$engine_size', 
            price = '$price', 
            image_url = '$image_url' 
            WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location:  cars.php");
        exit();
    } else {
        echo "Loi cap nhat: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa Xe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Chỉnh sửa Xe</h1>
        <form action="" method="POST">
            <input type="text" name="make" value="<?= $car['make'] ?>" placeholder="Hãng xe" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="model" value="<?= $car['model'] ?>" placeholder="Mẫu xe" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="registration" value="<?= $car['registration'] ?>" placeholder="Biển số" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="engine_size" value="<?= $car['engine_size'] ?>" placeholder="Dung tích động cơ" class="w-full p-2 border rounded mb-2" required>
            <input type="number" name="price" value="<?= $car['price'] ?>" placeholder="Giá xe" class="w-full p-2 border rounded mb-2" required>
            <input type="text" name="image_url" value="<?= $car['image_url'] ?>" placeholder="URL ảnh" class="w-full p-2 border rounded mb-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cập nhật</button>
        </form>
    </div>
</body>

</html>