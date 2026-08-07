# 06. Technical Learning Roadmap

## Mục tiêu học tập

Dự án này không chỉ là làm website ghi chú, mà là một hành trình học từ đầu đến cuối:

- Frontend development với Vue.js.
- Backend/API development với Laravel.
- Database design và migration.
- Testing cho frontend/backend.
- CI/CD bằng GitHub Actions.
- Docker hóa môi trường chạy.
- Infrastructure as Code bằng Terraform.
- Deployment và operations căn bản.

## Tech stack đã chốt

### Frontend

- Vue.js.
- Khuyến nghị dùng Vite để tạo project Vue hiện đại, nhẹ và nhanh.
- Có thể dùng Vue Router cho routing.
- Có thể dùng Pinia cho state management khi app bắt đầu phức tạp.
- Gọi API backend qua HTTP client như Axios hoặc `fetch`.

### Backend

- Laravel.
- Laravel cung cấp REST API cho frontend.
- Laravel migrations quản lý schema database.
- Laravel Eloquent ORM xử lý model và quan hệ dữ liệu.
- Laravel validation xử lý input từ frontend.
- Laravel Sanctum là lựa chọn phù hợp nếu cần auth cho SPA sau này.

### Database

- **Chốt cho MVP/staging/prod**: PostgreSQL. Postgres mang lại tính nhất quán, khả năng mở rộng và tính năng như `JSONB`/`tsvector` hữu ích cho notes/search.
- **Local/dev**: có thể dùng `SQLite` để khởi nhanh, nhưng khuyến nghị dockerized Postgres cho dev để tránh khác biệt behavior giữa môi trường.
- **Lưu ý**: xác định connection string qua env vars, chuẩn hoá `DATABASE_URL` trong `backend/.env`.

### Kiến trúc repository

Có hai hướng:

```text
my-note/
  frontend/   # Vue.js
  backend/    # Laravel
  infra/      # Terraform, Docker, deployment docs
  AI-Analyze/ # tài liệu phân tích
```

Hoặc tách thành hai repository riêng. Với mục tiêu học và quản lý đơn giản ban đầu, nên dùng **monorepo** như cấu trúc trên.

## Lý do chọn Vue.js + Laravel

- Tách rõ frontend và backend, phù hợp để học API thật.
- Laravel mạnh về CRUD, migration, validation, auth và test backend.
- Vue.js dễ tiếp cận, phù hợp để xây giao diện nhanh nhưng vẫn đủ chuyên nghiệp.
- CI/CD có nhiều phần để học: Node build cho frontend, Composer/PHP test cho backend.
- Docker và Terraform có ý nghĩa rõ hơn vì hệ thống có nhiều service: frontend, backend, database.

## Lộ trình học theo giai đoạn

### Stage 1: Local development

Cần học:

- Git basic.
- Cấu trúc monorepo.
- Tạo Vue.js project bằng Vite.
- Tạo Laravel project.
- Environment variables cho frontend/backend.
- Database local.
- Laravel migration.
- Seed data.

Kết quả mong muốn:

- Frontend Vue chạy local.
- Backend Laravel chạy local.
- Frontend gọi được API health check từ backend.
- Database có schema ban đầu.
- README setup local.

### Stage 2: Backend API với Laravel

Cần học:

- Route API trong Laravel.
- Controller.
- Request validation.
- Eloquent model.
- Migration.
- Resource/response format.
- Error handling cơ bản.

Nên làm trước:

- API CRUD Notes.
- API CRUD Goals.
- API cập nhật GoalLog.
- API lấy dữ liệu Calendar theo tháng/ngày.

### Stage 3: Frontend với Vue.js

Cần học:

- Vue component.
- Vue Router.
- Form binding.
- API call.
- Loading/error states.
- Component structure.
- Basic responsive layout.

Nên làm trước:

- Notes list/detail/form.
- Goals list/form/tracking.
- Calendar month view.
- Day detail panel.

### Stage 4: Testing

Cần học:

- Laravel feature test cho API.
- Laravel unit test cho business rule tính trạng thái goal.
- Frontend unit/component test nếu cần.
- Có thể thêm end-to-end test sau khi UI ổn.

Nên test trước:

- Tính status goal: not_done/partial/completed.
- CRUD notes API.
- CRUD goals API.
- Calendar query tổng hợp theo ngày/tháng.

### Stage 5: GitHub Actions CI

CI nên tách rõ frontend và backend.

Frontend workflow cần:

- Cài Node.
- Cài dependencies.
- Lint/test/build Vue.

Backend workflow cần:

- Cài PHP.
- Cài Composer dependencies.
- Chuẩn bị database test.
- Chạy Laravel test.

Ví dụ ý tưởng workflow:

```yaml
name: CI

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

jobs:
  frontend:
    runs-on: ubuntu-latest
    defaults:
      run:
        working-directory: frontend
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 22
      - run: npm ci
      - run: npm run lint
      - run: npm run test --if-present
      - run: npm run build

  backend:
    runs-on: ubuntu-latest
    defaults:
      run:
        working-directory: backend
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: "8.3"
      - run: composer install --no-interaction --prefer-dist
      - run: php artisan test
```

Cần học:

- Trigger workflow.
- Matrix job nếu muốn test nhiều version.
- Secret và environment.
- Cache npm/composer dependencies.
- Branch protection.

### Stage 6: Docker

Cần học:

- Dockerfile cho Laravel backend.
- Dockerfile hoặc static build cho Vue frontend.
- docker-compose cho frontend + backend + database.
- Nginx hoặc web server phục vụ frontend/backend.
- Healthcheck.

Kết quả:

- Chạy toàn bộ app bằng docker-compose.
- CI có thể build Docker image.

### Stage 7: Deployment

Hướng đề xuất:

- Để học triển khai thực tế: VPS/cloud VM + Docker Compose.
- Để deploy nhanh hơn: Render/Railway/Fly.io cho backend và frontend.
- Nếu muốn học hạ tầng bằng Terraform sâu hơn: AWS/GCP/Azure hoặc Cloudflare + managed database tùy ngân sách.

Cần quyết định:

- Có chấp nhận chi phí cloud không?
- Muốn deploy đơn giản hay muốn học hạ tầng sâu?
- Frontend deploy chung server với backend hay deploy tách riêng?

### Stage 8: Terraform

Cần học:

- Provider.
- Resource.
- Variable.
- Output.
- State.
- Remote backend.
- Workspace hoặc environment folder.

Cấu trúc gợi ý:

```text
infra/
  terraform/
    environments/
      dev/
      prod/
    modules/
      app/
      database/
      network/
```

MVP Terraform có thể bắt đầu nhỏ:

- Tạo VM/VPS/cloud resource nếu provider hỗ trợ.
- Tạo database managed, nếu dùng managed database.
- Tạo network/security group/firewall rule.
- Tạo output phục vụ deploy.

### Stage 9: CD

Cần học:

- Deploy sau khi CI pass.
- Environment approval.
- Secrets.
- Laravel migration khi deploy.
- Build frontend production.
- Rollback cơ bản.

Workflow mong muốn:

- Pull request: lint/test/build.
- Merge main: build + deploy dev.
- Tag/release: deploy production, nếu cần.

## Chủ đề nên học gắn với tính năng

- Notes CRUD -> Vue form, Laravel API, validation, database CRUD.
- Goals -> domain modeling, Laravel service/business rules, backend tests.
- Calendar -> data aggregation, UI state, date/time handling.
- Auth -> Laravel Sanctum, session/token, protected API routes.
- CI -> Node + PHP automation, quality gates.
- Docker -> chạy nhiều service thống nhất.
- Terraform -> reproducible infrastructure.
- Deploy -> environment, secrets, migration, monitoring.

## Rủi ro kỹ thuật ban đầu

- Scope quá rộng nếu làm đồng thời app + CI/CD + Terraform + deploy.
- Tách frontend/backend giúp học tốt nhưng tăng số lượng cấu hình.
- Date/time và repeat rule của goal có thể phức tạp nếu thiết kế quá tham.
- Terraform provider phụ thuộc vào nơi deploy, nên chưa nên viết chi tiết khi chưa chọn hosting/cloud.
- Auth và bảo mật dữ liệu cần nghiêm túc khi deploy public.

## Hướng xử lý

- Chốt MVP nhỏ.
- Làm frontend/backend local trước.
- CI sớm ngay khi có skeleton chạy được.
- Docker Compose sau khi API và UI cơ bản đã giao tiếp được.
- Terraform skeleton trước, hạ tầng chi tiết sau khi chọn provider.
- Mỗi giai đoạn đều có README ngắn để sau này đọc lại.
