<?php
include 'db.php';
header('Content-Type: application/json');

// Kích hoạt CORS để cho phép truy cập API công khai
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Xử lý định tuyến API
$request_method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);

// Xử lý yêu cầu API
switch ($request_method) {
    case 'GET':
        if ($segments[0] === 'api' && $segments[1] === 'grades') {
            // Endpoint: /api/grades/{student_id}/{school_year}/{semester}
            if (isset($segments[2], $segments[3], $segments[4])) {
                $student_id = (int)$segments[2];
                $school_year = $segments[3];
                $semester = (int)$segments[4];

                // Xác thực đầu vào
                if ($student_id <= 0 || !preg_match('/^\d{4}-\d{4}$/', $school_year) || !in_array($semester, [1, 2])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Dữ liệu đầu vào không hợp lệ']);
                    exit;
                }

                // Truy vấn cơ sở dữ liệu
                $sql = "SELECT h.id as hs_id, h.ten, h.lop, h.ngaysinh, h.gioitinh,
                               d.namhoc, d.hocky, d.toan, d.van, d.anh, d.ly, d.hoa, 
                               d.sinh, d.su, d.dia, d.gdcd, d.cn
                        FROM hocsinh h
                        JOIN diem d ON h.id = d.id_hocsinh
                        WHERE h.id = ? AND d.namhoc = ? AND d.hocky = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("isi", $student_id, $school_year, $semester);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $diem = $result->fetch_assoc();
                        $response = [
                            'student' => [
                                'id' => $diem['hs_id'],
                                'name' => $diem['ten'],
                                'class' => $diem['lop'],
                                'birthdate' => $diem['ngaysinh'],
                                'gender' => $diem['gioitinh'],
                            ],
                            'grades' => [
                                'school_year' => $diem['namhoc'],
                                'semester' => $diem['hocky'],
                                'scores' => [
                                    'math' => $diem['toan'],
                                    'literature' => $diem['van'],
                                    'english' => $diem['anh'],
                                    'physics' => $diem['ly'],
                                    'chemistry' => $diem['hoa'],
                                    'biology' => $diem['sinh'],
                                    'history' => $diem['su'],
                                    'geography' => $diem['dia'],
                                    'civic_education' => $diem['gdcd'],
                                    'technology' => $diem['cn']
                                ]
                            ]
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'Không tìm thấy dữ liệu']);
                    }
                    $stmt->close();
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Lỗi máy chủ']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Yêu cầu không hợp lệ']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint không tồn tại']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Phương thức không được hỗ trợ']);
        break;
}
?>