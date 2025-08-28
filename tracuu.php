<?php
include 'db.php';

$diem = null;

if (isset($_GET['timkiem'])) {
    $id_hocsinh = trim($_GET['id_hocsinh']);
    $namhoc = trim($_GET['namhoc']);
    $hocky = trim($_GET['hocky']);

    if ($id_hocsinh !== "" && $namhoc !== "" && $hocky !== "") {
        $sql = "SELECT h.id as hs_id, h.ten, h.lop, h.ngaysinh, h.gioitinh,
                       d.namhoc, d.hocky, d.toan, d.van, d.anh, d.ly, d.hoa, 
                       d.sinh, d.su, d.dia, d.gdcd, d.cn
                FROM hocsinh h
                JOIN diem d ON h.id = d.id_hocsinh
                WHERE h.id = ? AND d.namhoc = ? AND d.hocky = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isi", $id_hocsinh, $namhoc, $hocky);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $diem = $result->fetch_assoc();
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tra cứu điểm học sinh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Tra cứu điểm học sinh</h2>

    <!-- Form tra cứu -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-2">
            <input type="number" name="id_hocsinh" class="form-control" placeholder="ID học sinh" 
                   value="<?= isset($_GET['id_hocsinh']) ? htmlspecialchars($_GET['id_hocsinh']) : '' ?>" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="namhoc" class="form-control" placeholder="VD: 2024-2025" 
                   value="<?= isset($_GET['namhoc']) ? htmlspecialchars($_GET['namhoc']) : '' ?>" required>
        </div>
        <div class="col-md-2">
            <select name="hocky" class="form-select" required>
                <option value="">--Học kỳ--</option>
                <option value="1" <?= (isset($_GET['hocky']) && $_GET['hocky'] == 1) ? 'selected' : '' ?>>Học kỳ 1</option>
                <option value="2" <?= (isset($_GET['hocky']) && $_GET['hocky'] == 2) ? 'selected' : '' ?>>Học kỳ 2</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" name="timkiem" class="btn btn-primary">Tra cứu</button>
        </div>
    </form>

    <!-- Kết quả -->
    <?php if ($diem): ?>
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                Thông tin học sinh
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?= $diem['hs_id'] ?></p>
                <p><strong>Họ tên:</strong> <?= $diem['ten'] ?></p>
                <p><strong>Lớp:</strong> <?= $diem['lop'] ?></p>
                <p><strong>Ngày sinh:</strong> <?= $diem['ngaysinh'] ?></p>
                <p><strong>Giới tính:</strong> <?= $diem['gioitinh'] ?></p>
                <p><strong>Năm học:</strong> <?= $diem['namhoc'] ?> | <strong>Học kỳ:</strong> <?= $diem['hocky'] ?></p>
            </div>
        </div>

        <h4>Bảng điểm</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
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
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $diem['toan'] ?></td>
                    <td><?= $diem['van'] ?></td>
                    <td><?= $diem['anh'] ?></td>
                    <td><?= $diem['ly'] ?></td>
                    <td><?= $diem['hoa'] ?></td>
                    <td><?= $diem['sinh'] ?></td>
                    <td><?= $diem['su'] ?></td>
                    <td><?= $diem['dia'] ?></td>
                    <td><?= $diem['gdcd'] ?></td>
                    <td><?= $diem['cn'] ?></td>
                </tr>
            </tbody>
        </table>
    <?php elseif (isset($_GET['timkiem'])): ?>
        <div class="alert alert-warning">❌ Không tìm thấy dữ liệu phù hợp!</div>
    <?php endif; ?>
</div>
</body>
</html>
