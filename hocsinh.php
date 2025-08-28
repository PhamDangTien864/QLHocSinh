<?php
include 'db.php';

# --- Xử lý thêm học sinh ---
if (isset($_POST['them'])) {
    $ten       = $_POST['ten']       ?? '';
    $ngaysinh  = $_POST['ngaysinh']  ?? '';
    $gioitinh  = $_POST['gioitinh']  ?? '';
    $lop       = $_POST['lop']       ?? '';

    if ($ten !== '') {
        $sql = "INSERT INTO hocsinh(ten, ngaysinh, gioitinh, lop)
                VALUES('$ten', '$ngaysinh', '$gioitinh', '$lop')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Thêm học sinh thành công!</div>";
        } else {
            echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Tên học sinh không được để trống!</div>";
    }
}

# --- Xử lý xoá học sinh ---
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];
    $conn->query("DELETE FROM hocsinh WHERE id=$id");
}

# --- Xử lý tìm kiếm theo ID (nếu để trống thì hiện tất cả) ---
$sql = "SELECT * FROM hocsinh";
if (isset($_POST['timkiem'])) {
    $id_hocsinh = trim($_POST['id_hocsinh']);
    if ($id_hocsinh !== '') {
        $id_hocsinh = (int)$id_hocsinh;
        $sql = "SELECT * FROM hocsinh WHERE id = $id_hocsinh";
    }
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý học sinh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Quản lý học sinh</h2>

    <!-- Form thêm học sinh -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Thêm học sinh</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="ten" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="ngaysinh" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gioitinh" class="form-select">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lớp</label>
                    <input type="text" name="lop" class="form-control">
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" name="them" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form tìm kiếm theo ID -->
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="number" name="id_hocsinh" class="form-control" placeholder="Nhập ID học sinh">
        </div>
        <div class="col-md-2">
            <button type="submit" name="timkiem" class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>

    <!-- Danh sách học sinh -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Ngày sinh</th>
            <th>Giới tính</th>
            <th>Lớp</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()){ ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['ten']) ?></td>
                <td><?= htmlspecialchars($row['ngaysinh']) ?></td>
                <td><?= htmlspecialchars($row['gioitinh']) ?></td>
                <td><?= htmlspecialchars($row['lop']) ?></td>
                <td>
                    <a href="sua_hocsinh.php?id=<?= $row['id']?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="?xoa=<?= $row['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa học sinh này?')">Xóa</a>
                    <a href="bangdiem.php?id=<?= $row['id']?>" class="btn btn-info btn-sm">Bảng điểm</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
