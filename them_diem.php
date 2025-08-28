<?php
include 'db.php';

$id_hs = $_GET['id']; // id học sinh truyền từ trang hocsinh.php

if (isset($_POST['luu'])) {
    $namhoc = $_POST['namhoc'];
    $hocky = $_POST['hocky'];
    $toan=$_POST['toan']; $van=$_POST['van']; $anh=$_POST['anh'];
    $ly=$_POST['ly']; $hoa=$_POST['hoa']; $sinh=$_POST['sinh'];
    $su=$_POST['su']; $dia=$_POST['dia']; $gdcd=$_POST['gdcd'];

    $sql = "INSERT INTO diem(id_hocsinh,namhoc,hocky,toan,van,anh,ly,hoa,sinh,su,dia,gdcd)
            VALUES($id_hs,'$namhoc',$hocky,$toan,$van,$anh,$ly,$hoa,$sinh,$su,$dia,$gdcd)";
    if ($conn->query($sql)) {
        echo "✅ Thêm điểm thành công! <a href='hocsinh.php'>Quay lại</a>";
    } else {
        echo "❌ Lỗi: ".$conn->error;
    }
}
?>

<h2>Thêm điểm cho học sinh ID <?= $id_hs ?></h2>
<form method="post">
    Năm học: <input type="text" name="namhoc" required> 
    Học kỳ: <input type="number" name="hocky" min="1" max="2" required><br><br>
    Toán: <input type="number" step="0.1" name="toan" required>
    Văn: <input type="number" step="0.1" name="van" required>
    Anh: <input type="number" step="0.1" name="anh" required>
    Lý: <input type="number" step="0.1" name="ly" required>
    Hóa: <input type="number" step="0.1" name="hoa" required>
    Sinh: <input type="number" step="0.1" name="sinh" required><br><br>
    Sử: <input type="number" step="0.1" name="su" required>
    Địa: <input type="number" step="0.1" name="dia" required>
    GDCD: <input type="number" step="0.1" name="gdcd" required>
    <br><br>
    <button type="submit" name="luu">Lưu điểm</button>
</form>