<?php
include 'db.php';

// Lấy ID học sinh từ URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM hocsinh WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
} else {
    echo "Không tìm thấy học sinh!";
    exit;
}

// Xử lý cập nhật
if (isset($_POST['capnhat'])) {
    $ten = $_POST['ten'];
    $ngaysinh = $_POST['ngaysinh'];
    $lop = $_POST['lop'];
    $gioitinh = $_POST['gioitinh'];

    $sql_update = "UPDATE hocsinh SET ten='$ten', ngaysinh='$ngaysinh', lop='$lop', gioitinh='$gioitinh' WHERE id=$id";
    if ($conn->query($sql_update) === TRUE) {
        echo "<div class='alert alert-success'>Cập nhật thành công!</div>";
        header("refresh:1; url=hocsinh.php"); // quay về danh sách sau 1s
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin học sinh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h2 class="mb-4">Sửa thông tin học sinh</h2>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="ten" class="form-control" value="<?= $row['ten']?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="ngaysinh" class="form-control" value="<?= $row['ngaysinh']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select name="gioitinh" class="form-select">
                <option value="Nam" <?= ($row['gioitinh']=="Nam")?"selected":"" ?>>Nam</option>
                <option value="Nữ" <?= ($row['gioitinh']=="Nữ")?"selected":"" ?>>Nữ</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Lớp</label>
            <input type="text" name="lop" class="form-control" value="<?= $row['lop']?>">
        </div>
        <button type="submit" name="capnhat" class="btn btn-success">Lưu thay đổi</button>
        <a href="hocsinh.php" class="btn btn-secondary">Quay lại</a>
    </form>

</body>
</html>
