<?php
// Bật báo cáo lỗi để dễ dàng gỡ lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bao gồm tệp kết nối cơ sở dữ liệu
include 'db.php';

// Thiết lập tiêu đề (header) để trả về JSON
header('Content-Type: application/json');

// Lấy phương thức HTTP và các phần của URI
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$scriptName = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$resource = array_values(array_diff($requestUri, $scriptName));

// Hàm để trả về phản hồi JSON
function sendResponse($status, $message, $data = null) {
    http_response_code($status);
    $response = [
        'status' => $status,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

// Xử lý các endpoint API
switch ($resource[0] ?? '') {
    case 'hocsinh':
        $id = $resource[1] ?? null;
        if ($id !== null) {
            $id = (int)$id;
        }

        switch ($method) {
            case 'GET':
                if ($id) {
                    $stmt = $conn->prepare("SELECT * FROM hocsinh WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();
                    $stmt->close();
                    if ($data) {
                        sendResponse(200, 'Lấy thông tin học sinh thành công', $data);
                    } else {
                        sendResponse(404, 'Không tìm thấy học sinh');
                    }
                } else {
                    $result = $conn->query("SELECT * FROM hocsinh");
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    sendResponse(200, 'Lấy danh sách học sinh thành công', $data);
                }
                break;
            case 'POST':
                $input = json_decode(file_get_contents('php://input'), true);
                $ten = $input['ten'] ?? '';
                $ngaysinh = $input['ngaysinh'] ?? null;
                $gioitinh = $input['gioitinh'] ?? null;
                $lop = $input['lop'] ?? null;
                $stmt = $conn->prepare("INSERT INTO hocsinh(ten, ngaysinh, gioitinh, lop) VALUES(?, ?, ?, ?)");
                $stmt->bind_param("ssss", $ten, $ngaysinh, $gioitinh, $lop);
                if ($stmt->execute()) {
                    sendResponse(201, 'Thêm học sinh thành công', ['id' => $conn->insert_id]);
                } else {
                    sendResponse(500, 'Lỗi khi thêm học sinh', ['error' => $stmt->error]);
                }
                $stmt->close();
                break;
            case 'PUT':
                if (!$id) {
                    sendResponse(400, 'Thiếu ID học sinh');
                }
                $input = json_decode(file_get_contents('php://input'), true);
                $ten = $input['ten'] ?? '';
                $ngaysinh = $input['ngaysinh'] ?? null;
                $gioitinh = $input['gioitinh'] ?? null;
                $lop = $input['lop'] ?? null;
                $stmt = $conn->prepare("UPDATE hocsinh SET ten = ?, ngaysinh = ?, gioitinh = ?, lop = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $ten, $ngaysinh, $gioitinh, $lop, $id);
                if ($stmt->execute()) {
                    sendResponse(200, 'Cập nhật học sinh thành công');
                } else {
                    sendResponse(500, 'Lỗi khi cập nhật học sinh', ['error' => $stmt->error]);
                }
                $stmt->close();
                break;
            case 'DELETE':
                if (!$id) {
                    sendResponse(400, 'Thiếu ID học sinh');
                }
                $stmt = $conn->prepare("DELETE FROM hocsinh WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    sendResponse(200, 'Xóa học sinh thành công');
                } else {
                    sendResponse(500, 'Lỗi khi xóa học sinh', ['error' => $stmt->error]);
                }
                $stmt->close();
                break;
            default:
                sendResponse(405, 'Phương thức không được phép');
        }
        break;

    case 'diem':
        $id = $resource[1] ?? null;
        if ($id !== null) {
            $id = (int)$id;
        }
        
        // Check for specific grade lookup
        if (isset($_GET['namhoc']) && isset($_GET['hocky'])) {
            $namhoc = trim($_GET['namhoc']);
            $hocky = (int)$_GET['hocky'];
            if ($id > 0 && $namhoc !== '' && $hocky > 0) {
                $sql = "SELECT d.*, h.ten, h.lop FROM diem d JOIN hocsinh h ON d.id_hocsinh = h.id WHERE d.id_hocsinh = ? AND d.namhoc = ? AND d.hocky = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isi", $id, $namhoc, $hocky);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $stmt->close();
                if ($data) {
                    sendResponse(200, 'Lấy điểm thành công', $data);
                } else {
                    sendResponse(404, 'Không tìm thấy điểm phù hợp');
                }
            } else {
                 sendResponse(400, 'Vui lòng cung cấp đầy đủ ID học sinh, năm học và học kỳ.');
            }
        } else {
            switch ($method) {
                case 'GET':
                    if (!$id) {
                        sendResponse(400, 'Thiếu ID học sinh');
                    }
                    $stmt = $conn->prepare("SELECT * FROM diem WHERE id_hocsinh = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    if ($data) {
                        sendResponse(200, 'Lấy danh sách điểm thành công', $data);
                    } else {
                        sendResponse(404, 'Không tìm thấy điểm cho học sinh này');
                    }
                    break;
                case 'POST':
                    $input = json_decode(file_get_contents('php://input'), true);
                    $id_hocsinh = $input['id_hocsinh'] ?? 0;
                    $namhoc = $input['namhoc'] ?? null;
                    $hocky = $input['hocky'] ?? null;
                    $toan = $input['toan'] ?? null;
                    $van = $input['van'] ?? null;
                    // ... các môn khác
                    
                    $sql = "INSERT INTO diem(id_hocsinh, namhoc, hocky, toan, van, anh, ly, hoa, sinh, su, dia, gdcd, cn) 
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isidddddddddd", $id_hocsinh, $namhoc, $hocky, $toan, $van, $input['anh'], $input['ly'], $input['hoa'], $input['sinh'], $input['su'], $input['dia'], $input['gdcd'], $input['cn']);

                    if ($stmt->execute()) {
                        sendResponse(201, 'Thêm điểm thành công', ['id' => $conn->insert_id]);
                    } else {
                        sendResponse(500, 'Lỗi khi thêm điểm', ['error' => $stmt->error]);
                    }
                    $stmt->close();
                    break;
                case 'PUT':
                    if (!$id) {
                        sendResponse(400, 'Thiếu ID điểm');
                    }
                    $input = json_decode(file_get_contents('php://input'), true);
                    $toan = $input['toan'] ?? null;
                    $van = $input['van'] ?? null;
                    // ... các môn khác
                    
                    $sql = "UPDATE diem SET toan = ?, van = ?, anh = ?, ly = ?, hoa = ?, sinh = ?, su = ?, dia = ?, gdcd = ?, cn = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ddddddddddi", $toan, $van, $input['anh'], $input['ly'], $input['hoa'], $input['sinh'], $input['su'], $input['dia'], $input['gdcd'], $input['cn'], $id);
                    
                    if ($stmt->execute()) {
                        sendResponse(200, 'Cập nhật điểm thành công');
                    } else {
                        sendResponse(500, 'Lỗi khi cập nhật điểm', ['error' => $stmt->error]);
                    }
                    $stmt->close();
                    break;
                case 'DELETE':
                    if (!$id) {
                        sendResponse(400, 'Thiếu ID điểm');
                    }
                    $stmt = $conn->prepare("DELETE FROM diem WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        sendResponse(200, 'Xóa điểm thành công');
                    } else {
                        sendResponse(500, 'Lỗi khi xóa điểm', ['error' => $stmt->error]);
                    }
                    $stmt->close();
                    break;
                default:
                    sendResponse(405, 'Phương thức không được phép');
            }
        }
        break;

    default:
        sendResponse(404, 'Endpoint không tồn tại');
        break;
}

$conn->close();
?>