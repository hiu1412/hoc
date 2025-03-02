<?php
require_once './../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $query = "SELECT active FROM cars WHERE id = $id";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newStatus = $row['active'] ? 0 : 1;

        $updateQuery = "UPDATE cars SET active = $newStatus WHERE id = $id";
        $conn->query($updateQuery);
    }
}

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Xe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php require_once '../slidebar.php'; ?>
    <div class="ml-64 p-6">
        <h1 class="text-2xl font-bold mb-4">Danh sách Xe</h1>
        <a href="add_car.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Thêm Xe</a>
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Hãng</th>
                    <th class="p-3">Mẫu</th>
                    <th class="p-3">Biển số</th>
                    <th class="p-3">Dung tích</th>
                    <th class="p-3">Giá</th>
                    <th class="p-3">Ảnh</th>
                    <th class="p-3">Danh mục</th>
                    <th class="p-3">Trạng thái</th>
                    <th class="p-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <?php
                    // Lấy danh mục của xe
                    $carId = $row['id'];
                    $tagsQuery = "SELECT tags.name FROM car_tags 
                                  JOIN tags ON car_tags.tag_id = tags.id 
                                  WHERE car_tags.car_id = $carId";
                    $tagsResult = $conn->query($tagsQuery);

                    $tags = [];
                    while ($tagRow = $tagsResult->fetch_assoc()) {
                        $tags[] = $tagRow['name'];
                    }
                    ?>
                    <tr class='border-b text-center'>
                        <td class='p-3'><?php echo $row['id']; ?></td>
                        <td class='p-3'><?php echo $row['make']; ?></td>
                        <td class='p-3'><?php echo $row['model']; ?></td>
                        <td class='p-3'><?php echo $row['registration']; ?></td>
                        <td class='p-3'><?php echo $row['engine_size']; ?></td>
                        <td class='p-3'><?php echo number_format($row['price']); ?> VNĐ</td>
                        <td class='p-3'>
                            <img src='<?php echo $row['image_url']; ?>' alt='Car Image' class='w-16 h-16 object-cover rounded'>
                        </td>
                        <td class='p-3'>
                            <?php echo !empty($tags) ? implode(', ', $tags) : 'Không có danh mục'; ?>
                        </td>
                        <td class='p-3'>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="px-3 py-1 rounded <?php echo ($row['active'] ? 'bg-green-500 text-white' : 'bg-gray-500 text-white'); ?>">
                                    <?php echo ($row['active'] ? "Đang hiển thị" : "Đang ẩn"); ?>
                                </button>
                            </form>
                        </td>
                        <td class='p-3'>
                            <a href='car_detail.php?id=<?php echo $row['id']; ?>' class='text-blue-500'>Chi tiết</a> |
                            <a href='edit_car.php?id=<?php echo $row['id']; ?>' class='text-yellow-500'>Sửa</a> |
                            <form action='delete_car.php' method='POST' class='inline-block' onsubmit='return confirm("Bạn có chắc chắn muốn xóa xe này?");'>
                                <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                                <button type='submit' class='text-red-500'>Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>