<?php
require_once './../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php require_once '../slidebar.php'; ?>
    <div class="ml-64 p-6">
        <h1 class="text-2xl font-bold mb-4">Danh sách User</h1>
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Tên</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='border-b text-center'>
                            <td class='p-3'>{$row['id']}</td>
                            <td class='p-3'>{$row['username']}</td>
                            <td class='p-3'>{$row['email']}</td>
                            <td class='p-3'><a href='delete_user.php?id={$row['id']}' class='text-red-500'>Xóa</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>