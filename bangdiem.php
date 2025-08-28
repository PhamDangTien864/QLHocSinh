<?php
include 'db.php';

$id_hs = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin học sinh
$hs = $conn->query("SELECT * FROM hocsinh WHERE id=$id_hs")->fetch_assoc();

// Xử lý thêm điểm
if (isset($_POST['them_diem'])) {
    $namhoc = $_POST['namhoc'];
    $hocky = $_POST['hocky'];
    $toan = $_POST['toan'];
    $van = $_POST['van'];
    $anh = $_POST['anh'];
    $ly = $_POST['ly'];
    $hoa = $_POST['hoa'];
    $sinh = $_POST['sinh'];
    $su = $_POST['su'];
    $dia = $_POST['dia'];
    $gdcd = $_POST['gdcd'];
    $cn = $_POST['cn'];

    $sql = "INSERT INTO diem(id_hocsinh, namhoc, hocky, toan, van, anh, ly, hoa, sinh, su, dia, gdcd, cn) 
            VALUES($id_hs, '$namhoc', $hocky, $toan, $van, $anh, $ly, $hoa, $sinh, $su, $dia, $gdcd, $cn)";
    $conn->query($sql);
}

// Xử lý sửa điểm
if (isset($_POST['sua_diem'])) {
    $id_diem = $_POST['id_diem'];
    $namhoc = $_POST['namhoc'];
    $hocky = $_POST['hocky'];
    $toan = $_POST['toan'];
    $van = $_POST['van'];
    $anh = $_POST['anh'];
    $ly = $_POST['ly'];
    $hoa = $_POST['hoa'];
    $sinh = $_POST['sinh'];
    $su = $_POST['su'];
    $dia = $_POST['dia'];
    $gdcd = $_POST['gdcd'];
    $cn = $_POST['cn'];

    $sql = "UPDATE diem SET 
                namhoc='$namhoc', hocky=$hocky, 
                toan=$toan, van=$van, anh=$anh, ly=$ly, hoa=$hoa, sinh=$sinh, 
                su=$su, dia=$dia, gdcd=$gdcd, cn=$cn
            WHERE id=$id_diem";
    $conn->query($sql);
}

// Xử lý xóa điểm
if (isset($_GET['xoa_diem'])) {
    $id_diem = $_GET['xoa_diem'];
    $conn->query("DELETE FROM diem WHERE id=$id_diem");
}

$result = $conn->query("SELECT * FROM diem WHERE id_hocsinh=$id_hs ORDER BY namhoc, hocky");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm học sinh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
          crossorigin="anonymous">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Bảng điểm của học sinh: 
        <span class="text-primary"><?= $hs['ten']?></span> 
        (Lớp: <?= $hs['lop']?>)
    </h2>

    <!-- Form thêm điểm -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Thêm bảng điểm</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Năm học</label>
                    <input type="text" name="namhoc" class="form-control" placeholder="2024-2025" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Học kỳ</label>
                    <select name="hocky" class="form-select">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>

                <?php 
                $monhoc = ['toan'=>'Toán','van'=>'Văn','anh'=>'Anh','ly'=>'Lý','hoa'=>'Hóa','sinh'=>'Sinh','su'=>'Sử','dia'=>'Địa','gdcd'=>'GDCD','cn'=>'CN'];
                foreach($monhoc as $m=>$ten){ ?>
                    <div class="col-md-2">
                        <label class="form-label"><?= $ten?></label>
                        <input type="number" step="0.1" name="<?= $m?>" class="form-control" required>
                    </div>
                <?php } ?>

                <div class="col-12">
                    <button type="submit" name="them_diem" class="btn btn-success">Thêm điểm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách điểm -->
    <h3 class="mb-3">Danh sách bảng điểm</h3>
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Năm học</th>
                <th>Học kỳ</th>
                <th>Toán</th>
                <th>Văn</th>
                <th>Anh</th>
                <th>Lý</th>
                <th>Hóa</th>
                <th>Sinh</th>
                <th>Sử</th>
                <th>Địa</th>
                <th>GDCD</th>
                <th>Công nghệ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <form method="post">
                <td><?= $row['id']?></td>
                <td><input type="text" name="namhoc" value="<?= $row['namhoc']?>" class="form-control"></td>
                <td><input type="number" name="hocky" value="<?= $row['hocky']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="toan" value="<?= $row['toan']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="van" value="<?= $row['van']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="anh" value="<?= $row['anh']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="ly" value="<?= $row['ly']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="hoa" value="<?= $row['hoa']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="sinh" value="<?= $row['sinh']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="su" value="<?= $row['su']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="dia" value="<?= $row['dia']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="gdcd" value="<?= $row['gdcd']?>" class="form-control"></td>
                <td><input type="number" step="0.1" name="cn" value="<?= $row['cn']?>" class="form-control"></td>
                <td>
                    <input type="hidden" name="id_diem" value="<?= $row['id']?>">
                    <button type="submit" name="sua_diem" class="btn btn-warning btn-sm">Sửa</button>
                    <a href="bangdiem.php?id=<?= $id_hs?>&xoa_diem=<?= $row['id']?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Xóa bảng điểm này?')">Xóa</a>
                </td>
            </form>
        </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="hocsinh.php" class="btn btn-secondary mt-3">⬅ Quay lại danh sách học sinh</a>
</div>
</body>
</html>
