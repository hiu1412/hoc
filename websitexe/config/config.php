<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'websitechoixe';

$conn = new mysqLi($server, $user, $pass, $database);

if ($conn) {
    mysqli_query($conn, "SET NAME 'uft8' ");
} else {
    echo 'Ket noi that bai';
}
