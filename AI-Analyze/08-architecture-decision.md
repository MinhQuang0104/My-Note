# 08. Architecture Decision

## Quyết định hiện tại

Website **Project My Note** sẽ được xây theo kiến trúc tách frontend/backend:

- **Frontend**: Vue.js.
- **Backend**: Laravel.
- **API style**: REST API.
- **Database**: **PostgreSQL (chốt cho staging/prod)**. `SQLite` có thể dùng cho local/dev nhanh, nhưng production/staging dùng Postgres để tránh khác biệt về tính năng và hiệu năng.
- **Repository**: ưu tiên monorepo để dễ học và quản lý trong giai đoạn đầu.

## Cấu trúc thư mục đề xuất

```text
Project_My-Note/
  AI-Analyze/
  frontend/
    # Vue.js app
  backend/
    # Laravel app
  infra/
    docker/
    terraform/
  docs/
    # tài liệu kỹ thuật/phát triển nếu cần sau này
```

## Luồng giao tiếp hệ thống

```text
Người dùng
  -> Vue.js frontend
  -> Laravel REST API
  -> Database
```

Calendar không nên tự chứa toàn bộ logic nghiệp vụ. Frontend chỉ gọi API để lấy dữ liệu tổng hợp; backend chịu trách nhiệm truy vấn Goal, GoalLog, CalendarEvent và trả về dữ liệu phù hợp cho màn hình lịch.

## Backend Laravel nên chịu trách nhiệm

- Quản lý API routes.
- Xử lý CRUD Notes.
- Xử lý CRUD Goals.
- Xử lý GoalLog và tính trạng thái goal.
- Xử lý Calendar query tổng hợp.
- Validate dữ liệu đầu vào.
- Quản lý authentication sau này, khuyến nghị Laravel Sanctum nếu làm SPA.
- Quản lý migration/schema database.

## Lưu ý vận hành cho PostgreSQL

- **Migrations**: dùng Laravel migrations; test migration trên Postgres dev trước khi apply trên staging/prod.
- **Indexes**: thêm index trên `(user_id, created_at)` cho `notes`, `(goal_id, log_date)` cho `goal_logs`, và partial index cho `is_active` goals nếu cần.
- **JSONB**: sử dụng `JSONB` cho các trường metadata hoặc body linh hoạt (ví dụ `notes.content` nếu muốn lưu cấu trúc rich data).
- **Backups**: dùng managed Postgres với automated backups; có runbook cho restore và migration rollback.
- **Full-text**: cân nhắc `tsvector` cho search trên `notes` thay vì deploy search engine ngay từ đầu.

## Frontend Vue.js nên chịu trách nhiệm

- Hiển thị giao diện Notes, Goals, Calendar.
- Quản lý form và trạng thái UI.
- Gọi API backend.
- Hiển thị loading/error/success states.
- Điều hướng trang bằng Vue Router.
- Quản lý state bằng Pinia nếu cần.

## API module dự kiến

### Notes

- `GET /api/notes`
- `POST /api/notes`
- `GET /api/notes/{id}`
- `PUT /api/notes/{id}`
- `DELETE /api/notes/{id}`

### Goals

- `GET /api/goals`
- `POST /api/goals`
- `GET /api/goals/{id}`
- `PUT /api/goals/{id}`
- `DELETE /api/goals/{id}` hoặc `PATCH /api/goals/{id}/disable`

### Goal Logs

- `GET /api/goals/{id}/logs`
- `POST /api/goals/{id}/logs`
- `PUT /api/goals/{id}/logs/{logId}`

### Calendar

- `GET /api/calendar/month?year=2026&month=8`
- `GET /api/calendar/day?date=2026-08-06`

## CI/CD định hướng

GitHub Actions nên có ít nhất hai job:

- `frontend`: cài Node dependencies, lint/test/build Vue.
- `backend`: cài PHP/Composer dependencies, chạy Laravel test.

Sau khi có Docker/deploy:

- Build Docker image.
- Push image lên registry nếu cần.
- Deploy lên môi trường dev/prod.
- Chạy Laravel migrations có kiểm soát.

## Terraform định hướng

Terraform sẽ phụ thuộc vào nơi deploy. Trước khi chốt cloud/provider, chỉ nên tạo skeleton:

```text
infra/
  terraform/
    environments/
      dev/
      prod/
    modules/
```

Khi đã chọn provider, Terraform có thể quản lý:

- VM/app server.
- Managed database.
- Network/firewall/security group.
- DNS/domain nếu cần.
- Secret/environment integration nếu provider hỗ trợ.

## Quyết định còn mở

- Chọn database: PostgreSQL hay MySQL/MariaDB?
- Có dùng Docker ngay từ đầu không?
- Deploy frontend/backend chung server hay tách riêng?
- Chọn provider deploy nào để viết Terraform cụ thể?
- Auth sẽ làm trong MVP hay sau MVP?

