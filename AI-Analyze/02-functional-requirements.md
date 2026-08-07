# 02. Functional Requirements

## 1. Notes

### Mục tiêu

Cho phép người dùng tạo và quản lý ghi chú cá nhân cơ bản.

### Chức năng MVP

- Tạo ghi chú mới.
- Xem danh sách ghi chú.
- Xem chi tiết ghi chú.
- Sửa ghi chú.
- Xóa ghi chú.
- Tìm kiếm ghi chú theo tiêu đề/nội dung.

### Trường dữ liệu gợi ý

- Title.
- Content.
- Tags, có thể để sau MVP.
- Created date.
- Updated date.
- Archived status, có thể để sau MVP.

### Mở rộng sau

- Markdown editor.
- Pin note.
- Archive note.
- Tag/category.
- Link note với goal, calendar event hoặc task.
- Upload attachment.

## 2. Goals

### Mục tiêu

Cho phép người dùng tạo các mục tiêu linh hoạt, có thể theo dõi tiến độ theo ngày hoặc theo kỳ.

Ví dụ:

- Uống nước mỗi ngày.
- Tập thể dục 3 buổi/tuần.
- Học tiếng Anh 30 phút/ngày.
- Đọc sách 10 trang/ngày.

### Chức năng MVP

- Tạo mục tiêu mới.
- Xem danh sách mục tiêu.
- Sửa mục tiêu.
- Xóa/vô hiệu hóa mục tiêu.
- Ghi nhận tiến độ cho một ngày.
- Đánh dấu hoàn thành một mục tiêu trong ngày.
- Xem lịch sử tiến độ của một mục tiêu.

### Kiểu mục tiêu nên hỗ trợ từ đầu

Nên thiết kế Goal linh hoạt bằng các thuộc tính:

- **Boolean completion**: chỉ cần xong/chưa xong.
  - Ví dụ: "Tập thể dục hôm nay".
- **Numeric target**: cần nhập số lượng.
  - Ví dụ: "Uống 2 lít nước", "Học 30 phút".
- **Frequency target**: cần đạt số lần trong tuần/tháng.
  - Ví dụ: "Tập thể dục 3 lần/tuần".

MVP có thể ưu tiên Boolean và Numeric daily target trước.

### Trường dữ liệu gợi ý

- Name.
- Description.
- Goal type: boolean, numeric, frequency.
- Target value.
- Unit: ml, lít, phút, lần, trang, custom.
- Repeat rule: daily, weekly, monthly, custom.
- Start date.
- End date, optional.
- Active status.
- Color/icon, để hiển thị trên calendar.

## 3. Goal Tracking

### Mục tiêu

Ghi lại việc người dùng đã làm gì cho mục tiêu nào vào ngày nào.

### Chức năng MVP

- Chọn một mục tiêu.
- Nhập giá trị tiến độ trong ngày.
- Lưu tracking log.
- Tự động xác định trạng thái completed/partial/not done.
- Hiển thị trạng thái trên Calendar.

### Ví dụ nghiệp vụ

Goal: "Uống nước mỗi ngày"

- Target: 2 lít/ngày.
- Ngày 2026-08-06 nhập 1 lít: trạng thái partial.
- Ngày 2026-08-06 nhập thêm 1 lít: tổng 2 lít, trạng thái completed.
- Calendar ngày 2026-08-06 hiển thị goal "Uống nước" đã hoàn thành.

## 4. Calendar

### Mục tiêu

Là nơi xem tổng quan lịch trình, ghi chú liên quan và trạng thái goal theo ngày/tuần/tháng.

### Chức năng MVP

- Xem lịch theo tháng.
- Xem chi tiết một ngày khi click vào ngày.
- Hiển thị các goal đã hoàn thành/chưa hoàn thành trong ngày.
- Hiển thị event/lịch trình cơ bản, nếu làm trong MVP.

### Chức năng nên có sau MVP

- View theo ngày.
- View theo tuần.
- Tạo/sửa/xóa event.
- Gắn note vào event.
- Lọc theo loại: goal, note, event.
- Đồng bộ với Google Calendar, để sau.

## 5. Authentication

### Giai đoạn đầu

Nếu đây là app cá nhân chạy local/dev, có thể chưa cần auth.

### Khi deploy public

Cần có đăng nhập để bảo vệ dữ liệu cá nhân.

Chức năng:

- Đăng ký/đăng nhập.
- Đăng xuất.
- Bảo vệ route riêng tư.
- Mỗi user chỉ xem dữ liệu của mình.

## 6. Non-functional Requirements

- Giao diện đơn giản, dễ dùng trên desktop và mobile.
- Dữ liệu cá nhân cần được bảo vệ khi deploy.
- API rõ ràng để sau này mở rộng mobile/app khác.
- Có backup database ở giai đoạn deploy thật.
- Có CI/CD chạy test/build trước khi deploy.
- Infrastructure nên có thể tạo lại bằng Terraform.

