<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ql_hocsinh";

// Kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>