# 07. Open Questions

## Sản phẩm

1. Website này chỉ dùng cá nhân hay sau này muốn cho người khác đăng ký?
2. Ghi chú có cần Markdown/rich text ngay từ đầu không?
3. Notes có cần tag/category trong MVP không?
4. Mục tiêu có cần streak ngay trong MVP không?
5. Goal tracking nên là:
   - Nhập tổng giá trị hiện tại trong ngày?
   - Hay mỗi lần nhập là một entry cộng dồn?
6. Calendar MVP chỉ cần month view hay cần cả day/week view?
7. Lịch chung có cần tạo event ngay trong MVP không, hay chỉ hiển thị goal status trước?
8. Có cần reminder/notification không?

## Nghiệp vụ Goal

1. Mục tiêu "uống nước mỗi ngày" hoàn thành khi đạt target, ví dụ 2 lít/ngày. Vậy nếu vượt target có ghi nhận over-achieved không?
2. Mục tiêu weekly như "tập thể dục 3 lần/tuần" sẽ hiển thị trên calendar như thế nào?
3. Nếu quên cập nhật hôm qua, có cho phép backfill không?
4. Có cần pause goal trong một khoảng thời gian không?
5. Có cần archived goal để giữ lịch sử thay vì xóa hẳn không?

## Kỹ thuật

1. Bạn muốn dùng database nào?
   - **Đã chốt**: PostgreSQL cho staging/prod. `SQLite` cho local/dev nếu cần khởi nhanh.
   - MySQL/MariaDB?
2. Bạn muốn deploy lên đâu?
   - Vercel/Render/Railway/Fly.io?
   - VPS?
   - AWS/GCP/Azure?
3. Frontend và backend deploy chung một server hay tách riêng?
4. Bạn có sẵn sàng dùng cloud có phí không?
5. Bạn muốn Terraform dùng provider nào?
6. Bạn có muốn Docker hóa ứng dụng ngay từ đầu không?
7. Mức độ test mong muốn ở MVP là cơ bản hay nghiêm túc?

## Học tập

1. Mục tiêu học chính của dự án là frontend, backend, DevOps hay full-stack đều nhau?
2. Bạn muốn mỗi giai đoạn có bài học/tài liệu riêng không?
3. Bạn muốn code theo kiểu production-like hay ưu tiên nhanh để thấy kết quả?
4. Bạn muốn AI đóng vai trò nào trong các session sau?
   - BA phân tích yêu cầu.
   - Solution Architect.
   - Senior Developer.
   - DevOps Engineer.
   - Reviewer/mentor.

## Quyết định tạm thời để tiếp tục

Nếu chưa trả lời hết, có thể tạm lấy các giả định sau:

- App dùng cho một người dùng cá nhân trước.
- Tech stack dùng Vue.js frontend + Laravel backend.
- Cấu trúc repository ưu tiên monorepo: `frontend/`, `backend/`, `infra/`.
- MVP có Notes CRUD, Goals boolean/numeric, Calendar month view.
- Chưa cần reminder, Google Calendar sync, analytics nâng cao.
- Data model để sẵn user_id để sau này thêm auth.
- CI tạo sớm, Terraform chỉ làm skeleton cho đến khi chọn provider deploy.
