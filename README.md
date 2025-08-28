# API Tra cứu điểm học sinh

API RESTful công khai để tra cứu điểm học sinh dựa trên ID học sinh, năm học và học kỳ.

## Cài đặt

1. **Yêu cầu**:
   - PHP >= 8.0
   - MySQL/MariaDB
   - Web server (Apache/Nginx)

2. **Cấu hình cơ sở dữ liệu**:
   - Tạo cơ sở dữ liệu `ql_hocsinh` sử dụng tệp `ql_hocsinh.sql`.
   - Cập nhật thông tin kết nối cơ sở dữ liệu trong `db.php`.

3. **Triển khai API**:
   - Sao chép tệp `api.php` vào thư mục gốc của máy chủ web.
   - Đảm bảo tệp `db.php` nằm cùng thư mục với `api.php`.

4. **Kiểm tra API**:
   - Sử dụng công cụ như Postman hoặc Swagger UI để gửi yêu cầu tới API.
   - Đường dẫn Swagger: `http://your-domain.com/swagger.yaml` (tùy thuộc vào nơi bạn lưu trữ tệp Swagger).

## Cách sử dụng

### Endpoint
- **GET** `/api/grades/{student_id}/{school_year}/{semester}`
  - **Mô tả**: Tra cứu điểm của học sinh dựa trên ID, năm học và học kỳ.
  - **Tham số**:
    - `student_id` (bắt buộc): ID của học sinh (số nguyên).
    - `school_year` (bắt buộc): Năm học, định dạng `YYYY-YYYY` (VD: 2024-2025).
    - `semester` (bắt buộc): Học kỳ, giá trị `1` hoặc `2`.
  - **Ví dụ yêu cầu**:
    ```bash
    GET http://your-domain.com/api/grades/8/2023-2024/1