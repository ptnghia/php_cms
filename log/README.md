# Hệ Thống Bảo Mật - LifeTech Beyond Ecom

Hệ thống bảo mật này được thiết kế để bảo vệ website khỏi các cuộc tấn công phổ biến như DDoS, SQL Injection, XSS, và các hành vi đáng ngờ khác. Hệ thống bao gồm các thành phần chính sau:

---

## Thành Phần Chính

1. **`ddos_protection.php`**:

   - Bảo vệ website khỏi các cuộc tấn công DDoS.
   - Ghi log các yêu cầu vào cơ sở dữ liệu.
   - Chặn các IP có hành vi đáng ngờ.
   - Bỏ qua kiểm tra đối với các bot hợp lệ (Googlebot, Bingbot, v.v.).

2. **`attack_analysis.php`**:

   - Phân tích log để tìm các IP đáng ngờ.
   - Gửi báo cáo qua email đến quản trị viên.
   - Tự động xóa log cũ hơn 1 tháng.
   - Kiểm tra và ghi log các bot hợp lệ bị chặn nhầm.

3. **`attack_report.php`**:

   - Hiển thị báo cáo trực quan về các cuộc tấn công.
   - Bao gồm biểu đồ số lượng yêu cầu theo thời gian và phân tích IP đáng ngờ.

4. **`.htaccess`**:

   - Bảo vệ các file nhạy cảm như `.env`, `config.php`, và các file log.
   - Chặn các client độc hại và giới hạn phương thức truy cập.
   - Bật Gzip và cache trình duyệt để tối ưu hiệu suất.
   - Cho phép truy cập vào các file quan trọng như `robots.txt` và `sitemap.xml`.

5. **`whitelist_ips.txt`**:
   - Danh sách các IP đáng tin cậy (whitelist) được bỏ qua trong quá trình kiểm tra.

---

## Hướng Dẫn Cấu Hình

### 1. **Cơ Sở Dữ Liệu**

Đảm bảo các bảng cần thiết đã được tạo trong cơ sở dữ liệu. Chạy các lệnh SQL sau để tạo bảng:

```sql
CREATE TABLE log_ddos_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    request_uri TEXT NOT NULL,
    timestamp INT NOT NULL
);

CREATE TABLE log_blocked_ips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL UNIQUE,
    user_agent TEXT NOT NULL,
    timestamp INT NOT NULL
);

CREATE TABLE log_nonexistent_php_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    request_uri TEXT NOT NULL,
    timestamp INT NOT NULL
);
```

---

### 2. **Tích Hợp Hệ Thống**

#### **a. Tích Hợp `ddos_protection.php`**

- Mở file `index.php` của hệ thống.
- Thêm đoạn mã sau để tích hợp `ddos_protection.php`:
  ```php
  // Include DDoS protection
  if (file_exists('log/ddos_protection.php')) {
      include 'log/ddos_protection.php';
      execute_protection($db); // Gọi hàm bảo vệ DDoS
  } else {
      die('DDoS protection file not found.');
  }
  ```

#### **b. Tích Hợp `attack_analysis.php`**

- Thiết lập **cron job** để chạy file này định kỳ (ví dụ: mỗi ngày hoặc mỗi tuần).
- Lệnh cron job:
  ```bash
  php d:\xampp\htdocs\lifetech\003-beyond-ecom\log\attack_analysis.php
  ```
- Ví dụ: Chạy mỗi ngày lúc 00:00:
  ```
  0 0 * * * php d:\xampp\htdocs\lifetech\003-beyond-ecom\log\attack_analysis.php
  ```

#### **c. Tích Hợp `attack_report.php`**

- Truy cập file qua trình duyệt tại URL:
  ```
  http://<domain>/log/attack_report.php
  ```
  Ví dụ:
  ```
  http://localhost/003-beyond-ecom/log/attack_report.php
  ```

---

### 3. **Cấu Hình Email**

- Mở file `smtp/index.php`.
- Cập nhật các thông tin sau:
  ```php
  define('SMTP_HOST', 'smtp.gmail.com'); // Máy chủ SMTP
  define('SMTP_PORT', 465);             // Cổng SMTP
  define('SMTP_USER', 'your-email@gmail.com'); // Email gửi
  define('SMTP_PASS', 'your-email-password'); // Mật khẩu email
  ```
- **Lưu ý bảo mật**: Sử dụng biến môi trường hoặc file cấu hình bên ngoài để lưu trữ mật khẩu SMTP.

---

### 4. **Cấu Hình Whitelist**

- Mở file `whitelist_ips.txt`.
- Thêm các IP đáng tin cậy vào file, mỗi IP trên một dòng. Ví dụ:
  ```
  127.0.0.1
  ::1
  192.168.1.100
  ```

---

### 5. **Cấu Hình `.htaccess`**

- Đảm bảo các file quan trọng như `robots.txt` và `sitemap.xml` có thể truy cập công khai:
  ```ini
  <FilesMatch "^(robots\.txt|sitemap\.xml)$">
      Require all granted
  </FilesMatch>
  ```
- Cho phép phương thức `HEAD` để hỗ trợ các bot tìm kiếm:
  ```ini
  RewriteCond %{REQUEST_METHOD} !^(GET|POST|HEAD) [NC]
  RewriteRule .* - [F,L]
  ```

---

### 6. **Cấu Hình Kết Nối Cơ Sở Dữ Liệu**

Nếu bạn sử dụng mã PHP khác để kết nối cơ sở dữ liệu, hãy đảm bảo cấu hình chính xác. Ví dụ:

```php
<?php
// Cấu hình kết nối cơ sở dữ liệu
$host = 'localhost'; // Địa chỉ máy chủ
$dbname = 'your_database_name'; // Tên cơ sở dữ liệu
$username = 'your_username'; // Tên người dùng
$password = 'your_password';

try {
    // Tạo kết nối PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Kết nối cơ sở dữ liệu thành công!";
} catch (PDOException $e) {
    // Xử lý lỗi kết nối
    die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
}
?>
```

- **Lưu ý bảo mật**: Không lưu thông tin nhạy cảm như mật khẩu trong mã nguồn. Sử dụng biến môi trường hoặc file cấu hình bên ngoài để lưu trữ thông tin này.

---

## Hướng Dẫn Sử Dụng

### 1. **Kiểm Tra DDoS Protection**

- Gửi nhiều yêu cầu từ cùng một IP để kiểm tra xem IP có bị chặn không.
- Kiểm tra bảng `log_ddos_logs` và `log_blocked_ips` trong cơ sở dữ liệu để đảm bảo log được ghi lại chính xác.

### 2. **Xem Báo Cáo**

- Truy cập `attack_report.php` để xem báo cáo trực quan.
- Báo cáo bao gồm:
  - Số lượng yêu cầu theo thời gian.
  - Danh sách các IP đáng ngờ nhất.
  - Đề xuất bảo mật.

### 3. **Nhận Báo Cáo Qua Email**

- Đảm bảo cron job cho `attack_analysis.php` hoạt động.
- Kiểm tra email để nhận báo cáo định kỳ.

---

## Lợi Ích

1. **Bảo Mật Cao**:

   - Ngăn chặn các cuộc tấn công DDoS, SQL Injection, XSS, và các hành vi đáng ngờ khác.

2. **Quản Lý Dễ Dàng**:

   - Báo cáo trực quan giúp quản trị viên theo dõi tình hình bảo mật.

3. **Tối Ưu Hóa**:
   - Tự động xóa log cũ để giảm tải cơ sở dữ liệu.

---

## Lưu Ý

- **DDoS Phân Tán**: Hệ thống hiện tại không đủ mạnh để chống lại các cuộc tấn công DDoS phân tán từ nhiều IP. Cân nhắc sử dụng dịch vụ chống DDoS chuyên nghiệp như **Cloudflare** hoặc **AWS Shield**.
- **Bảo Mật Email**: Sử dụng biến môi trường để lưu trữ mật khẩu SMTP thay vì lưu trực tiếp trong mã nguồn.
- **Giám Sát Thời Gian Thực**: Tích hợp thêm công cụ giám sát như **Zabbix**, **Nagios**, hoặc **Prometheus** để phát hiện và phản ứng nhanh với các cuộc tấn công.
- **Tương Thích PHP 7.4**: Hệ thống đã được kiểm tra và tương thích với PHP 7.4. Đảm bảo rằng các extension cần thiết như `pdo_mysql`, `mbstring`, và `json` được bật trong cấu hình PHP.

---

## Liên Hệ

Nếu bạn cần hỗ trợ hoặc có bất kỳ câu hỏi nào, vui lòng liên hệ:

- **Email**: kythuat.lifetech@gmail.com
- **Website**: [LifeTech Media](https://lifetech-media.vn/)
