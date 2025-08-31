API Tra cứu điểm học sinh
API RESTful mã nguồn mở để tra cứu và quản lý thông tin học sinh, điểm số theo ID học sinh, năm học và học kỳ. Dự án bao gồm giao diện web để quản lý học sinh và điểm số.
Yêu cầu

PHP >= 8.0
MySQL/MariaDB
Web server (Apache/Nginx)
Postman hoặc Swagger UI để kiểm tra API

Cài đặt

Tạo cơ sở dữ liệu:

Nhập file ql_hocsinh.sql vào MySQL/MariaDB để tạo database ql_hocsinh và dữ liệu mẫu.
Command: mysql -u root -p ql_hocsinh < ql_hocsinh.sql


Cấu hình kết nối database:

Sao chép db.example.php thành db.php.
Cập nhật thông tin trong db.php (servername, username, password, dbname).


Triển khai dự án:

Sao chép tất cả file vào thư mục gốc của web server (ví dụ: /var/www/html trên Linux hoặc htdocs trên XAMPP).
Đảm bảo module mod_rewrite được bật trên Apache để .htaccess hoạt động.


Kiểm tra API:

Mở trình duyệt hoặc Postman, gửi yêu cầu đến endpoint (ví dụ: http://localhost/api/grades/8/2023/1).
Xem tài liệu API tại /docs/swagger.yaml bằng Swagger UI.



Cách sử dụng
Endpoint
1. GET /api/grades/{student_id}/{school_year}/{semester}

Mô tả: Tra cứu điểm của học sinh theo ID, năm học và học kỳ.
Tham số:
student_id: ID học sinh (số nguyên).
school_year: Năm học (VD: 2023).
semester: Học kỳ (1 hoặc 2).


Ví dụ yêu cầu:GET http://localhost/api/grades/8/2023/1


Ví dụ phản hồi:{
  "status": 200,
  "message": "Lấy điểm thành công",
  "data": {
    "student": {
      "id": 8,
      "name": "Mai",
      "class": "11A1",
      "birthdate": "2025-07-31",
      "gender": "Nữ"
    },
    "grades": {
      "school_year": "2023",
      "semester": 1,
      "scores": {
        "math": 5,
        "literature": 4.9,
        "english": 5,
        "physics": 5,
        "chemistry": 5,
        "biology": 5,
        "history": 5,
        "geography": 5,
        "civic_education": 5,
        "technology": 5
      }
    }
  }
}



2. GET /api/hocsinh

Mô tả: Lấy danh sách tất cả học sinh.
Ví dụ yêu cầu:GET http://localhost/api/hocsinh


Ví dụ phản hồi:{
  "status": 200,
  "message": "Lấy danh sách học sinh thành công",
  "data": [
    {
      "id": 1,
      "ten": "Long",
      "ngaysinh": "2025-08-13",
      "gioitinh": "Nam",
      "lop": "10A1"
    },
    {
      "id": 8,
      "ten": "Mai",
      "ngaysinh": "2025-07-31",
      "gioitinh": "Nữ",
      "lop": "11A1"
    }
  ]
}



3. GET /api/hocsinh/{id}

Mô tả: Lấy thông tin một học sinh theo ID.
Ví dụ yêu cầu:GET http://localhost/api/hocsinh/8


Ví dụ phản hồi:{
  "status": 200,
  "message": "Lấy thông tin học sinh thành công",
  "data": {
    "id": 8,
    "ten": "Mai",
    "ngaysinh": "2025-07-31",
    "gioitinh": "Nữ",
    "lop": "11A1"
  }
}



4. POST /api/hocsinh

Mô tả: Thêm học sinh mới.
Body (JSON):{
  "ten": "Nguyen Van A",
  "ngaysinh": "2023-01-01",
  "gioitinh": "Nam",
  "lop": "10A2"
}


Ví dụ yêu cầu:POST http://localhost/api/hocsinh
Content-Type: application/json


Ví dụ phản hồi:{
  "status": 201,
  "message": "Thêm học sinh thành công",
  "data": {
    "id": 9
  }
}



5. PUT /api/hocsinh/{id}

Mô tả: Cập nhật thông tin học sinh.
Body (JSON):{
  "ten": "Nguyen Van B",
  "ngaysinh": "2023-01-01",
  "gioitinh": "Nam",
  "lop": "10A3"
}


Ví dụ yêu cầu:PUT http://localhost/api/hocsinh/8
Content-Type: application/json


Ví dụ phản hồi:{
  "status": 200,
  "message": "Cập nhật học sinh thành công"
}



6. DELETE /api/hocsinh/{id}

Mô tả: Xóa học sinh theo ID.
Ví dụ yêu cầu:DELETE http://localhost/api/hocsinh/8


Ví dụ phản hồi:{
  "status": 200,
  "message": "Xóa học sinh thành công"
}



7. GET /api/diem/{id_hocsinh}

Mô tả: Lấy danh sách điểm của học sinh theo ID.
Ví dụ yêu cầu:GET http://localhost/api/diem/8


Ví dụ phản hồi:{
  "status": 200,
  "message": "Lấy danh sách điểm thành công",
  "data": [
    {
      "id": 3,
      "id_hocsinh": 8,
      "namhoc": "2023",
      "hocky": 1,
      "toan": 5,
      "van": 4.9,
      "anh": 5,
      "ly": 5,
      "hoa": 5,
      "sinh": 5,
      "su": 5,
      "dia": 5,
      "gdcd": 5,
      "cn": 5
    }
  ]
}



8. POST /api/diem

Mô tả: Thêm điểm mới cho học sinh.
Body (JSON):{
  "id_hocsinh": 8,
  "namhoc": "2023",
  "hocky": 1,
  "toan": 7.5,
  "van": 8.0,
  "anh": 6.5,
  "ly": 7.0,
  "hoa": 7.5,
  "sinh": 8.0,
  "su": 6.0,
  "dia": 6.5,
  "gdcd": 7.0,
  "cn": 7.5
}


Ví dụ yêu cầu:POST http://localhost/api/diem
Content-Type: application/json


Ví dụ phản hồi:{
  "status": 201,
  "message": "Thêm điểm thành công",
  "data": {
    "id": 6
  }
}



9. PUT /api/diem/{id}

Mô tả: Cập nhật điểm theo ID điểm.
Body (JSON):{
  "toan": 8.0,
  "van": 7.5,
  "anh": 7.0,
  "ly": 6.5,
  "hoa": 7.0,
  "sinh": 7.5,
  "su": 6.5,
  "dia": 7.0,
  "gdcd": 8.0,
  "cn": 7.5
}


Ví dụ yêu cầu:PUT http://localhost/api/diem/3
Content-Type: application/json


Ví dụ phản hồi:{
  "status": 200,
  "message": "Cập nhật điểm thành công"
}



10. DELETE /api/diem/{id}

Mô tả: Xóa điểm theo ID.
Ví dụ yêu cầu:DELETE http://localhost/api/diem/3


Ví dụ phản hồi:{
  "status": 200,
  "message": "Xóa điểm thành công"
}



Kiểm tra API

Swagger UI:
Copy nội dung docs/swagger.yaml vào Swagger Editor.
Hoặc lưu trữ swagger.yaml trên server và truy cập qua Swagger UI.


Postman:
Tải Postman từ postman.com.
Gửi yêu cầu đến các endpoint với header Content-Type: application/json.
Ví dụ: Gửi GET tới http://localhost/api/grades/8/2023/1.



Giao diện Web

Truy cập index.php để vào trang chủ.
Quản lý học sinh: hocsinh.php (thêm, sửa, xóa, tra cứu).
Tra cứu điểm: tracuu.php.
Quản lý điểm: bangdiem.php và them_diem.php.

License
MIT License. Xem chi tiết trong file LICENSE.
Liên hệ

GitHub: [PhamDangTien864]
Email: [tien96669@st.vimaru.ed.vn]
