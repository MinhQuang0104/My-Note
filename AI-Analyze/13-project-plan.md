# 13. Project Plan

## Mục tiêu

Xây dựng MVP `Project My Note` local-first với:
- Multi-user authentication/authorization.
- Notes CRUD.
- Goals + append-entry tracking.
- Calendar day/week view.
- UI chính: thanh navbar trái + nội dung chính phải.
- Backend Laravel + PostgreSQL 15 (Docker local).
- Frontend Vue.js (Vite).
- CI/CD cơ bản với GitHub Actions.

## Phạm vi giai đoạn đầu

### Phase 1: Skeleton & local infrastructure

- Tạo monorepo cơ bản: `frontend/`, `backend/`, `infra/`.
- Thiết lập Docker Compose local với:
  - `backend` (Laravel PHP-FPM)
  - `db` (Postgres 15)
  - `frontend` (Vue dev server)
- Khởi tạo Laravel app và Vue app.
- Thiết lập `.env` cho backend và frontend.
- Đảm bảo local chạy được:
  - `backend` có thể truy cập DB Postgres.
  - `frontend` có thể gọi API backend qua proxy/CORS.

### Phase 2: Authentication & user model

- Cài Laravel Sanctum.
- Implement API auth:
  - `POST /api/auth/register`
  - `POST /api/auth/login`
  - `POST /api/auth/logout`
  - `GET /api/auth/me`
- Tạo model `User` và migration.
- Bảo vệ tất cả route API cần auth.
- Triển khai middleware kiểm tra authorization.

### Phase 3: Notes MVP

- Tạo migration và model `Note`.
- Build API CRUD cho notes.
- Frontend:
  - Notes list page.
  - Note detail/edit page.
  - Create note form.
- UI layout: sidebar trái có mục `Notes`, `Goals`, `Calendar`, `Profile`.
- Main area hiển thị nội dung tương ứng.

### Phase 4: Goals MVP

- Tạo migration và model `Goal`, `GoalEntry`.
- Implement API Goals và Goal Entries.
- Goal tracking với append entry flow.
- Frontend:
  - Goals list.
  - Goal detail + history.
  - Add entry form.
- Tính status ngày dựa trên tổng `GoalEntry`.

### Phase 5: Calendar MVP

- Implement Calendar API day/week.
- Backend tổng hợp data từ notes, goals, entries, events.
- Frontend:
  - Calendar sidebar/toolbar controls.
  - Day view chi tiết.
  - Week view summary.
- UI chính: vẫn navbar trái và nội dung chính phải.

### Phase 6: Polishing local dev & CI

- Thêm standard response format.
- Validation/error handling.
- CORS + CSP basic headers.
- GitHub Actions workflow:
  - `frontend` build/test.
  - `backend` test.
  - Docker build.
- Local runbook xong.

## UI layout guide

### Tổng quan

- `AppShell` chia làm hai cột:
  - `Sidebar` bên trái: navigation, user info, quick actions.
  - `MainContent` bên phải: hiển thị màn hình hiện tại.
- Sidebar luôn cố định và tối ưu cho desktop.
- Main content hỗ trợ bảng, thẻ và tab đơn giản.

### Sidebar dự kiến

- Logo / app name.
- Menu:
  - Dashboard / Home
  - Notes
  - Goals
  - Calendar
  - Events (nếu thêm sau)
  - Settings / Profile
- Nút tạo nhanh Note / Goal.

### Main content

- Page header.
- Section detail / list.
- Page-level actions ở góc trên phải.
- Responsive khi màn hình nhỏ: sidebar gọn hoặc ẩn.

## Kế hoạch task chi tiết

### Sprint 1: Setup & Auth

1. Tạo monorepo structure.
2. Docker Compose với Postgres 15.
3. Laravel project + Vue project.
4. Backend auth với Sanctum.
5. Frontend login/register UI.
6. Local chạy `auth` + API auth được.

### Sprint 2: Notes

1. Notes migration + model.
2. Notes API CRUD.
3. Notes UI list/detail/edit.
4. Search/archived filter cơ bản.
5. Ensure note ownership / authorization.

### Sprint 3: Goals

1. Goal + GoalEntry migrations.
2. Goals API + entries.
3. Goal tracking logic và status.
4. Goals UI list/detail/history.
5. Add entry UI.

### Sprint 4: Calendar

1. Calendar day/week API.
2. Calendar UI.
3. Calendar ngày hiển thị trạng thái goal.
4. Tích hợp notes/goals trong day view.

### Sprint 5: Polish & CI

1. Standard API response.
2. Error handling.
3. CORS/CSP/config security.
4. GitHub Actions CI.
5. Docker image build.
6. Local runbook + README.

## Local deployment

- Môi trường chính: local bằng Docker Compose.
- Không cần staging/prod ban đầu.
- Chạy nhanh bằng:

```bash
cd d:\Workspace\Tu_Hoc\Project_My-Note
docker compose up -d
```

- Laravel migrate:

```bash
docker compose exec backend php artisan migrate
```

- Frontend chạy dev server hoặc serve build.

## Hướng dẫn triển khai Phase 1: Skeleton & local infrastructure

### Mục tiêu Phase 1

Đảm bảo bạn có thể chạy được toàn bộ project local với:
- frontend Vue.js hiển thị một màn hình cơ bản,
- backend Laravel trả về API health check,
- PostgreSQL 15 chạy qua Docker,
- frontend có thể gọi backend.

### Bước 1: Chuẩn bị môi trường

Yêu cầu cài sẵn:
- Docker Desktop
- Docker Compose
- Node.js 22+
- PHP 8.3+
- Composer
- Git

Kiểm tra cài đặt:

```bash
docker --version
docker compose version
node --version
php --version
composer --version
```

### Bước 2: Tạo structure ban đầu

Trong workspace đã có:
- `frontend/`
- `backend/`
- `infra/docker/`
- `infra/terraform/`
- `docs/`

Không cần tạo thêm thư mục phức tạp ở giai đoạn đầu.

### Bước 3: Khởi tạo backend Laravel

Tạo project Laravel trong `backend/`:

```bash
cd d:\Workspace\Tu_Hoc\Project_My-Note\backend
composer create-project laravel/laravel .
```

Sau khi tạo xong:
- mở file `.env`
- cấu hình database cho PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=my_note
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### Bước 4: Khởi tạo frontend Vue.js

Tạo app Vue trong `frontend/`:

```bash
cd d:\Workspace\Tu_Hoc\Project_My-Note\frontend
npm create vite@latest . -- --template vue
npm install
```

Sau khi tạo xong:
- cài thêm `axios` nếu cần gọi API.

```bash
npm install axios
```

### Bước 5: Tạo Docker Compose cho local

Tạo file `docker-compose.yml` ở root project:

```yaml
services:
  db:
    image: postgres:15
    container_name: my-note-db
    environment:
      POSTGRES_DB: my_note
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: postgres
    ports:
      - "5432:5432"
    volumes:
      - postgres_data:/var/lib/postgresql/data

  backend:
    image: php:8.3-fpm
    container_name: my-note-backend
    working_dir: /var/www
    volumes:
      - ./backend:/var/www
    depends_on:
      - db
    ports:
      - "8000:8000"

  frontend:
    image: node:22
    container_name: my-note-frontend
    working_dir: /app
    volumes:
      - ./frontend:/app
    ports:
      - "5173:5173"
    command: sh -c "npm install && npm run dev -- --host 0.0.0.0"

volumes:
  postgres_data:
```

> Ghi chú: file này có thể dùng làm bản đầu tiên để chạy local. Sau đó bạn có thể đổi sang Dockerfile riêng cho backend/frontend nếu cần.

### Bước 6: Chạy database trước

```bash
cd d:\Workspace\Tu_Hoc\Project_My-Note
docker compose up -d db
```

Kiểm tra DB hoạt động:

```bash
docker compose ps
```

### Bước 7: Chạy backend Laravel

Trong `backend/`:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan serve --host 0.0.0.0 --port 8000
```

Nếu dùng Docker, có thể chạy backend trong container thay vì host.

### Bước 8: Chạy frontend Vue

Trong `frontend/`:

```bash
npm install
npm run dev -- --host 0.0.0.0
```

Sau đó mở:
- frontend: `http://localhost:5173`
- backend: `http://localhost:8000`

### Bước 9: Kiểm tra kết nối giữa frontend và backend

Tạo API test đầu tiên trong Laravel:

```php
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
```

Frontend hiện tại có thể gọi thử:

```js
axios.get('http://localhost:8000/api/health')
```

### Bước 10: Acceptance criteria cho Phase 1

Phase 1 coi như hoàn thành khi:
- Docker Compose chạy được DB.
- Laravel chạy được và trả về API health.
- Vue app chạy được và hiển thị một màn hình cơ bản.
- Frontend có thể gọi backend thành công.
- Bạn có thể chạy lại toàn bộ hệ thống bằng vài lệnh đơn giản.

### Gợi ý ưu tiên triển khai

1. Đầu tiên chạy DB bằng Docker.
2. Sau đó chạy Laravel backend và API health.
3. Sau đó chạy Vue frontend.
4. Cuối cùng nối frontend với backend bằng Axios.

### Ghi chú

- Bỏ qua AWS service chi tiết hiện tại.
- Tập trung hoàn thiện backend + frontend local.
- Sau khi local ổn, có thể thêm AWS deployment plan tiếp.
