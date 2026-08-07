# 05. MVP Scope

## Mục tiêu MVP

Có một website cá nhân chạy được, lưu dữ liệu thật, gồm:

- Quản lý ghi chú cơ bản.
- Tạo mục tiêu linh hoạt.
- Cập nhật tiến độ mục tiêu trong ngày.
- Xem lịch tháng và trạng thái hoàn thành mục tiêu.
- Có CI build/test.
- Có bước deploy đầu tiên.

## MVP nên bao gồm

### Notes

- Create note.
- List notes.
- View note detail.
- Edit note.
- Delete note.

### Goals

- Create goal.
- List active goals.
- Edit goal.
- Disable/delete goal.
- Support boolean goal.
- Support numeric daily goal.
- Update today's progress.
- View basic goal history.

### Calendar

- Month view.
- Highlight today.
- Show goal completion indicators by date.
- Click date to see goals and logs for that date.

### Technical

- Source code quản lý bằng Git.
- GitHub repository.
- Frontend dùng Vue.js.
- Backend dùng Laravel.
- Backend cung cấp REST API cho frontend.
- **Database**: PostgreSQL cho staging/prod; dùng Laravel migrations để quản lý schema. `SQLite` chỉ cho local/dev nhanh nếu cần.
- GitHub Actions workflow:
  - install dependencies
  - lint
  - test
  - build
- Docker/development environment nên được chuẩn hóa sớm để học triển khai.
- Database migration or schema setup.
- Basic deployment environment.
- Terraform skeleton cho hạ tầng.

## MVP không nên bao gồm ngay

- Collaboration/multi-user sharing.
- Google Calendar sync.
- Reminder notification.
- Mobile app riêng.
- AI note summarization.
- Advanced analytics/streak phức tạp.
- Attachment upload.
- Rich text editor phức tạp.

## Giai đoạn đề xuất

### Phase 0: Project setup và phân tích

Kết quả:

- Tài liệu phân tích trong `AI-Analyze`.
- Chốt tech stack: Vue.js + Laravel.
- Tạo repo Git.
- Tạo skeleton frontend Vue.js.
- Tạo skeleton backend Laravel.
- Tạo cấu trúc repository phù hợp cho frontend/backend.

### Phase 1: Notes CRUD

Kết quả:

- Có UI Vue.js cho ghi chú.
- Có API Laravel cho ghi chú.
- Có database migration/model/controller cho ghi chú.
- Có test cơ bản.
- Có workflow CI đầu tiên.

### Phase 2: Goals và tracking

Kết quả:

- Tạo goal boolean/numeric.
- Cập nhật tiến độ hằng ngày.
- Tính status goal.
- Lưu GoalLog.

### Phase 3: Calendar

Kết quả:

- Month calendar.
- Click date xem detail.
- Goal completion hiển thị trên lịch.

### Phase 4: Auth và deploy

Kết quả:

- Đăng nhập bảo vệ dữ liệu.
- App deploy lên môi trường public/private.
- Secret được cấu hình qua environment.

### Phase 5: Terraform và CI/CD hoàn chỉnh

Kết quả:

- Terraform tạo hạ tầng.
- Pipeline build/test/deploy rõ ràng.
- Có môi trường dev/prod nếu cần.
- Có tài liệu vận hành cơ bản.

## Definition of Done cho MVP

- Chạy app local được bằng README.
- Tạo/sửa/xóa note thành công.
- Tạo goal và cập nhật tiến độ thành công.
- Calendar hiển thị trạng thái goal theo ngày.
- Dữ liệu không mất khi reload app.
- CI chạy thành công trên GitHub Actions.
- Có ít nhất một cách deploy app.
- Tài liệu hướng dẫn setup và deploy đủ để làm lại.
