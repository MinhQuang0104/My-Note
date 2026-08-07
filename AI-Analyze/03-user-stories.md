# 03. User Stories

## Epic 1: Notes

### US-N01: Tạo ghi chú

Là người dùng, tôi muốn tạo một ghi chú mới để lưu lại thông tin cá nhân.

Acceptance criteria:

- Có form nhập title và content.
- Khi bấm Save, ghi chú được lưu.
- Sau khi lưu, ghi chú xuất hiện trong danh sách.
- Nếu title/content bắt buộc bị trống, hệ thống hiển thị lỗi phù hợp.

### US-N02: Xem danh sách ghi chú

Là người dùng, tôi muốn xem danh sách ghi chú để tìm lại thông tin đã lưu.

Acceptance criteria:

- Danh sách hiển thị title, thời gian cập nhật gần nhất.
- Ghi chú mới/cập nhật gần đây nên hiển thị trước.
- Có trạng thái empty khi chưa có ghi chú.

### US-N03: Sửa ghi chú

Là người dùng, tôi muốn sửa ghi chú để cập nhật nội dung.

Acceptance criteria:

- Có thể mở ghi chú cũ và chỉnh sửa.
- Khi Save, nội dung mới được lưu.
- Updated date được cập nhật.

### US-N04: Xóa ghi chú

Là người dùng, tôi muốn xóa ghi chú không còn cần thiết.

Acceptance criteria:

- Có nút xóa trong chi tiết hoặc danh sách.
- Nên có xác nhận trước khi xóa.
- Ghi chú bị xóa không còn hiển thị trong danh sách.

## Epic 2: Goals

### US-G01: Tạo mục tiêu linh hoạt

Là người dùng, tôi muốn tạo mục tiêu mới với loại và đơn vị tùy chỉnh để theo dõi nhiều thói quen khác nhau.

Acceptance criteria:

- Có thể nhập tên mục tiêu.
- Có thể chọn kiểu mục tiêu: complete/not complete hoặc numeric.
- Nếu numeric, có thể nhập target value và unit.
- Có thể chọn tần suất lặp lại daily/weekly/monthly.
- Mục tiêu mới xuất hiện trong danh sách active goals.

### US-G02: Cập nhật tiến độ mục tiêu trong ngày

Là người dùng, tôi muốn nhập tiến độ của hôm nay để biết mình đã đạt mục tiêu chưa.

Acceptance criteria:

- Có thể chọn mục tiêu cần cập nhật.
- Với boolean goal, có thể bấm mark as done.
- Với numeric goal, có thể nhập giá trị tiến độ.
- Hệ thống tính trạng thái: not done, partial, completed.
- Kết quả được lưu theo ngày.

### US-G03: Xem lịch sử tiến độ

Là người dùng, tôi muốn xem lịch sử hoàn thành của một mục tiêu để theo dõi sự kiên trì.

Acceptance criteria:

- Màn hình chi tiết goal hiển thị các ngày đã tracking.
- Mỗi ngày có giá trị tiến độ và trạng thái.
- Có thể nhận biết ngày completed và ngày partial.

## Epic 3: Calendar

### US-C01: Xem lịch tháng

Là người dùng, tôi muốn xem lịch theo tháng để nhìn tổng quan ngày nào đã hoàn thành mục tiêu.

Acceptance criteria:

- Calendar hiển thị đúng các ngày trong tháng.
- Ngày hiện tại được đánh dấu.
- Ngày có goal completed có indicator riêng.
- Khi click một ngày, hiển thị chi tiết ngày đó.

### US-C02: Xem chi tiết một ngày

Là người dùng, tôi muốn xem chi tiết một ngày để biết hôm đó có lịch gì và goal nào đã hoàn thành.

Acceptance criteria:

- Hiển thị danh sách goals của ngày.
- Mỗi goal có trạng thái not done/partial/completed.
- Nếu có event/lịch trình, hiển thị trong cùng màn hình chi tiết ngày.

### US-C03: Tạo lịch trình cơ bản

Là người dùng, tôi muốn tạo một lịch trình trong ngày để quản lý việc cần làm.

Acceptance criteria:

- Có thể tạo event với title, start time, end time.
- Event xuất hiện trên ngày tương ứng trong calendar.
- Có thể sửa/xóa event.

Ghi chú: US-C03 có thể đẩy sang sau MVP nếu muốn tập trung Notes + Goals trước.

## Epic 4: Learning/Delivery

### US-L01: CI build

Là developer, tôi muốn GitHub Actions tự động chạy lint/test/build khi có push hoặc pull request.

Acceptance criteria:

- Workflow chạy khi push/PR vào main.
- Nếu test/build fail, workflow fail.
- Badge/status có thể xem trên GitHub.

### US-L02: Terraform infrastructure

Là developer, tôi muốn dùng Terraform để tạo hạ tầng ứng dụng để có thể học IaC và tái tạo môi trường.

Acceptance criteria:

- Có thư mục terraform riêng.
- Có biến cấu hình cho môi trường dev/prod.
- Có README hướng dẫn init/plan/apply.
- State được quản lý đúng cách khi chuyển sang deploy thật.

### US-L03: Deployment pipeline

Là developer, tôi muốn pipeline có thể deploy ứng dụng sau khi build thành công.

Acceptance criteria:

- CI tách rõ build/test và deploy.
- Deploy chỉ chạy với branch/environment phù hợp.
- Secret không hard-code trong source.

