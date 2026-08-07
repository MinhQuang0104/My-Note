# 12. CI/CD Plan + Docker + AWS Deployment

## 1. Overview

- Backend Laravel + frontend Vue.js (monorepo).
- Local dev: Docker Compose với Postgres 15 và app containers.
- CI/CD: GitHub Actions.
- Docker image build và push lên registry.
- Deploy AWS bằng Docker container.
- Release workflow: merge → staging → production.
- Mục tiêu chi phí thấp trên AWS.

## 2. Local development setup

- `docker-compose.yml` chứa:
  - `backend` service (Laravel)
  - `frontend` service (Vue)
  - `db` service (`postgres:15`)
- Các môi trường local dùng compose overrides.
- `.env` file cho backend và frontend.
- `backend` kết nối `pgsql` tới `db`.

## 3. Docker strategy

### Backend Dockerfile

- Base image: `php:8.3-fpm` hoặc `php:8.3-fpm-alpine`.
- Cài `pdo_pgsql`, `pgsql`, `gd`, `zip`, `openssl`, `composer`.
- Copy source, cài dependency composer, chạy `php artisan config:cache`.
- Expose port `9000`.

### Frontend Dockerfile

- Base image: `node:22-alpine`.
- Cài dependencies, build Vite app.
- Nếu deploy frontend qua backend hoặc Nginx, dùng multi-stage build.

### Registry

- AWS ECR là lựa chọn natural với AWS.
- GitHub Actions có thể push image tới ECR.
- Lưu trữ image tag theo commit SHA và branch.

## 4. GitHub Actions workflow

### Jobs

1. `frontend-build`
   - Chạy ở `frontend/`
   - Cài Node 22
   - `npm ci`
   - `npm run lint` (nếu có)
   - `npm run build`
   - Tạo artifact hoặc build image nếu deploy frontend container.

2. `backend-test`
   - Chạy ở `backend/`
   - Cài PHP 8.3
   - Cài composer dependencies
   - Thiết lập Postgres service trong job
   - `php artisan test`

3. `docker-build-and-push`
   - Dùng Docker Buildx.
   - Build backend image.
   - Tag image với `latest`, branch, và commit SHA.
   - Push lên AWS ECR.

4. `deploy`
   - Chạy khi merge vào branch `main` hoặc `release`.
   - Deploy container lên AWS.

### Trigger

- `workflow_dispatch`
- `pull_request` nhánh `main`
- `push` nhánh `main`

### Example job flow

- `pull_request`: chạy `frontend-build` + `backend-test`.
- `push` vào `main`: chạy `frontend-build` + `backend-test` + `docker-build-and-push` + `deploy`.

## 5. AWS deployment options

### 5.1 AWS ECS Fargate + ECR

- Triển khai Docker container lên ECS Fargate.
- Giảm chi phí quản lý server.
- Sử dụng 1 task nhỏ (0.25 vCPU / 0.5 GB) cho giai đoạn đầu.
- Mỗi service chạy 1 task.
- Dùng Fargate service với awsvpc networking.

### 5.2 AWS RDS Postgres (free-tier)

- Sử dụng `db.t4g.micro` hoặc `db.t3.micro` nếu đủ free tier.
- Chọn Postgres 15.
- Có cấu hình tự động backup mặc định, nhưng ban đầu bạn có thể tắt nếu chưa cần.
- Kết nối từ backend container tới RDS endpoint.

### 5.3 AWS Application Load Balancer

- Dùng ALB trước ECS service.
- HTTPS terminate tại ALB.
- Cấu hình security group chỉ mở port 443.

### 5.4 Thay thế thấp chi phí

- Nếu muốn cực kỳ đơn giản và rẻ, có thể dùng AWS Lightsail container hoặc App Runner.
- Lightsail container có chi phí cố định nhỏ hơn và phù hợp với MVP.
- App Runner có triển khai trực tiếp từ ECR.

## 6. Deployment flow

1. Push code lên GitHub.
2. GitHub Actions build/test.
3. Nếu merge vào `main`, Actions push Docker image lên ECR.
4. Actions gọi deploy lên ECS/Fargate.
5. Backend container lấy cấu hình từ Secrets/Env.
6. Database Postgres chạy trên RDS.

## 7. Environment strategy

- Local: Docker Compose.
- Staging: optional, không bắt buộc ban đầu.
- Production: AWS ECS + RDS.
- `main` branch deploy production.

## 8. Secrets and config

- Sử dụng GitHub Secrets:
  - `AWS_ACCESS_KEY_ID`
  - `AWS_SECRET_ACCESS_KEY`
  - `AWS_REGION`
  - `AWS_ACCOUNT_ID`
  - `ECR_REPOSITORY`
  - `DB_HOST`
  - `DB_PORT`
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`
  - `APP_KEY`
  - `APP_URL`

- Dùng AWS Secrets Manager hoặc Parameter Store nếu muốn mở rộng.

## 9. HTTPS, CORS, CSP, bảo mật dữ liệu

- HTTPS: cấu hình ALB terminates TLS.
- CORS: giới hạn chỉ frontend origin.
- CSP: cấu hình header trong backend hoặc reverse proxy.
- User data protection: xác thực token, bảo vệ route, sanitize input.
- Không ghi secret vào source.

## 10. Minimal runbook

### Local setup

- Chạy `docker-compose up -d`.
- `docker compose exec backend php artisan migrate`
- `docker compose exec backend php artisan db:seed` (nếu cần)
- Truy cập app ở `http://localhost:3000` hoặc `http://localhost:8000`.

### Build & deploy

- Kiểm tra `main` branch.
- Push code.
- Quan sát GitHub Actions.
- Nếu deploy thất bại, xem logs ở Actions và ECS task logs.

### Rollback

- Nếu cần rollback, tag image trước đó và update service task definition sang image cũ.
- Tránh xóa image cũ ngay lập tức.

## 11. Next step

- Viết GitHub Actions template trong `.github/workflows/ci-cd.yml`.
- Tạo `docker-compose.yml` và Dockerfiles.
- Tạo ECS task definition và ECR repository.
