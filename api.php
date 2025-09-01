<?php
// Bật báo cáo lỗi để dễ dàng gỡ lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bao gồm tệp kết nối cơ sở dữ liệu
include 'db.php';

// Thiết lập tiêu đề để trả về JSON
header('Content-Type: application/json');

// Lấy phương thức HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Lấy URL từ rewrite (từ .htaccess)
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Phân tách URL thành các phần
$resource = explode('/', trim($url, '/'));

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

// Kết nối đến database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    sendResponse(500, "Kết nối database thất bại: " . $conn->connect_error);
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
                if (!isset($input['ten']) || empty(trim($input['ten']))) {
                    sendResponse(400, 'Tên học sinh là bắt buộc');
                }
                $ten = trim($input['ten']);
                $ngaysinh = $input['ngaysinh'] ?? null;
                $gioitinh = $input['gioitinh'] ?? null;
                $lop = $input['lop'] ?? null;
                $stmt = $conn->prepare("INSERT INTO hocsinh (ten, ngaysinh, gioitinh, lop) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $ten, $ngaysinh, $gioitinh, $lop);
                if ($stmt->execute()) {
                    sendResponse(201, 'Thêm học sinh thành công', ['id' => $conn->insert_id]);
                } else {
                    sendResponse(500, 'Lỗi khi thêm học sinh', ['error' => $conn->error]);
                }
                $stmt->close();
                break;

            case 'PUT':
                if (!$id) {
                    sendResponse(400, 'Thiếu ID học sinh');
                }
                $input = json_decode(file_get_contents('php://input'), true);
                if (!isset($input['ten']) || empty(trim($input['ten']))) {
                    sendResponse(400, 'Tên học sinh là bắt buộc');
                }
                $ten = trim($input['ten']);
                $ngaysinh = $input['ngaysinh'] ?? null;
                $gioitinh = $input['gioitinh'] ?? null;
                $lop = $input['lop'] ?? null;
                $stmt = $conn->prepare("UPDATE hocsinh SET ten = ?, ngaysinh = ?, gioitinh = ?, lop = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $ten, $ngaysinh, $gioitinh, $lop, $id);
                if ($stmt->execute()) {
                    sendResponse(200, 'Cập nhật học sinh thành công');
                } else {
                    sendResponse(500, 'Lỗi khi cập nhật học sinh', ['error' => $conn->error]);
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
                    sendResponse(500, 'Lỗi khi xóa học sinh', ['error' => $conn->error]);
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

        switch ($method) {
            case 'GET':
                if ($id) {
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
                } else {
                    sendResponse(400, 'Thiếu ID học sinh');
                }
                break;

            case 'POST':
                $input = json_decode(file_get_contents('php://input'), true);
                if (!isset($input['id_hocsinh']) || !isset($input['namhoc']) || !isset($input['hocky'])) {
                    sendResponse(400, 'Thiếu thông tin id_hocsinh, namhoc hoặc hocky');
                }
                $id_hocsinh = (int)$input['id_hocsinh'];
                $namhoc = $input['namhoc'];
                $hocky = (int)$input['hocky'];
                $toan = isset($input['toan']) ? floatval($input['toan']) : null;
                $van = isset($input['van']) ? floatval($input['van']) : null;
                $anh = isset($input['anh']) ? floatval($input['anh']) : null;
                $ly = isset($input['ly']) ? floatval($input['ly']) : null;
                $hoa = isset($input['hoa']) ? floatval($input['hoa']) : null;
                $sinh = isset($input['sinh']) ? floatval($input['sinh']) : null;
                $su = isset($input['su']) ? floatval($input['su']) : null;
                $dia = isset($input['dia']) ? floatval($input['dia']) : null;
                $gdcd = isset($input['gdcd']) ? floatval($input['gdcd']) : null;
                $cn = isset($input['cn']) ? floatval($input['cn']) : null;

                $sql = "INSERT INTO diem (id_hocsinh, namhoc, hocky, toan, van, anh, ly, hoa, sinh, su, dia, gdcd, cn) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isidddddddddd", $id_hocsinh, $namhoc, $hocky, $toan, $van, $anh, $ly, $hoa, $sinh, $su, $dia, $gdcd, $cn);
                if ($stmt->execute()) {
                    sendResponse(201, 'Thêm điểm thành công', ['id' => $conn->insert_id]);
                } else {
                    sendResponse(500, 'Lỗi khi thêm điểm', ['error' => $conn->error]);
                }
                $stmt->close();
                break;

            case 'PUT':
                if (!$id) {
                    sendResponse(400, 'Thiếu ID điểm');
                }
                $input = json_decode(file_get_contents('php://input'), true);
                $toan = isset($input['toan']) ? floatval($input['toan']) : null;
                $van = isset($input['van']) ? floatval($input['van']) : null;
                $anh = isset($input['anh']) ? floatval($input['anh']) : null;
                $ly = isset($input['ly']) ? floatval($input['ly']) : null;
                $hoa = isset($input['hoa']) ? floatval($input['hoa']) : null;
                $sinh = isset($input['sinh']) ? floatval($input['sinh']) : null;
                $su = isset($input['su']) ? floatval($input['su']) : null;
                $dia = isset($input['dia']) ? floatval($input['dia']) : null;
                $gdcd = isset($input['gdcd']) ? floatval($input['gdcd']) : null;
                $cn = isset($input['cn']) ? floatval($input['cn']) : null;

                $sql = "UPDATE diem SET toan = ?, van = ?, anh = ?, ly = ?, hoa = ?, sinh = ?, su = ?, dia = ?, gdcd = ?, cn = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ddddddddddi", $toan, $van, $anh, $ly, $hoa, $sinh, $su, $dia, $gdcd, $cn, $id);
                if ($stmt->execute()) {
                    sendResponse(200, 'Cập nhật điểm thành công');
                } else {
                    sendResponse(500, 'Lỗi khi cập nhật điểm', ['error' => $conn->error]);
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
                    sendResponse(500, 'Lỗi khi xóa điểm', ['error' => $conn->error]);
                }
                $stmt->close();
                break;

            default:
                sendResponse(405, 'Phương thức không được phép');
        }
        break;

    case 'grades':
        if (count($resource) >= 4) {
            $student_id = (int)$resource[1];
            $school_year = $resource[2];
            $semester = (int)$resource[3];

            if (!preg_match('/^\d{4}$/', $school_year) || !in_array($semester, [1, 2])) {
                sendResponse(400, 'Năm học hoặc học kỳ không hợp lệ');
            }

            $sql = "SELECT h.id, h.ten AS name, h.lop AS class, h.ngaysinh AS birthdate, h.gioitinh AS gender,
                           d.namhoc AS school_year, d.hocky AS semester, 
                           d.toan AS math, d.van AS literature, d.anh AS english, d.ly AS physics,
                           d.hoa AS chemistry, d.sinh AS biology, d.su AS history, d.dia AS geography,
                           d.gdcd AS civic_education, d.cn AS technology
                    FROM hocsinh h
                    LEFT JOIN diem d ON h.id = d.id_hocsinh AND d.namhoc = ? AND d.hocky = ?
                    WHERE h.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $school_year, $semester, $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();

            if ($data) {
                $response = [
                    'student' => [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'class' => $data['class'],
                        'birthdate' => $data['birthdate'],
                        'gender' => $data['gender']
                    ],
                    'grades' => [
                        'school_year' => $data['school_year'] ?: $school_year,
                        'semester' => $data['semester'] ?: $semester,
                        'scores' => [
                            'math' => $data['math'] ?? null,
                            'literature' => $data['literature'] ?? null,
                            'english' => $data['english'] ?? null,
                            'physics' => $data['physics'] ?? null,
                            'chemistry' => $data['chemistry'] ?? null,
                            'biology' => $data['biology'] ?? null,
                            'history' => $data['history'] ?? null,
                            'geography' => $data['geography'] ?? null,
                            'civic_education' => $data['civic_education'] ?? null,
                            'technology' => $data['technology'] ?? null
                        ]
                    ]
                ];
                sendResponse(200, 'Lấy điểm thành công', $response);
            } else {
                sendResponse(404, 'Không tìm thấy điểm cho học sinh này');
            }
        } else {
            sendResponse(400, 'Định dạng endpoint không hợp lệ');
        }
        break;

    default:
        sendResponse(404, 'Endpoint không tồn tại');
}

$conn->close();
?>