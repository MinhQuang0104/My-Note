# 14. Current State Analysis

Lan quet: 2026-08-17 15:18:03 +07:00

## Tong quan repository

`Project_My-Note` hien la monorepo gom:

- `frontend/`: Vue 3 + Vite app.
- `backend/`: Laravel API backend.
- `infra/`: skeleton tai lieu Docker/Terraform.
- `.github/workflows/ci.yml`: GitHub Actions workflow.
- `AI-Analyze/`: tai lieu phan tich va ke hoach.

## Backend

Stack hien tai:

- PHP `^8.3`.
- Laravel framework `^13.8`.
- Laravel Sanctum `^4.3`.
- PHPUnit `^12.5.12`.

Route API trong `backend/routes/api.php`:

- Public:
  - `GET /api/health`
  - `POST /api/auth/register`
  - `POST /api/auth/login`
- Can `auth:sanctum`:
  - `POST /api/auth/logout`
  - `GET /api/auth/me`
  - `apiResource /api/notes`
  - `apiResource /api/goals`
  - `apiResource /api/goals/{goal}/entries`
  - `GET /api/calendar`

Controller da co:

- `AuthController`: register, login, logout, me; tra ve token Sanctum dang plain JSON.
- `NoteController`: list latest, create, show, update, delete; co kiem tra owner.
- `GoalController`: list latest, create, show, update, delete; co kiem tra owner.
- `GoalEntryController`: list/create/update/delete theo goal; co kiem tra goal owner va entry thuoc goal.
- `CalendarController`: tra ve 10 notes, 10 goals, 10 entries moi nhat cua user.

Model hien tai:

- `User`: co quan he `notes`, `goals`, `goalEntries`, dung `HasApiTokens`.
- `Note`: `fillable = user_id, title, content, is_archived`.
- `Goal`: `fillable = user_id, title, description, target_date, is_completed`.
- `GoalEntry`: `fillable = goal_id, user_id, label, note, entry_date`.

## Database

Migration domain hien co:

- `notes`: `user_id`, `title`, `content`, `is_archived`, timestamps.
- `goals`: `user_id`, `title`, `description`, `target_date`, `is_completed`, timestamps.
- `goal_entries`: `goal_id`, `user_id`, `label`, `note`, `entry_date`, timestamps.
- Sanctum personal access tokens migration da co.
- Laravel default tables cho users, cache, jobs da co.

PostgreSQL local:

- `docker-compose.yml` dung `postgres:15-alpine`.
- DB: `my_note`.
- User/password: `postgres`/`postgres`.

## Frontend

Stack hien tai:

- Vue `^3.5.40`.
- Vite `^8.2.0`.

Luon chinh:

- `App.vue`: sidebar dieu huong 3 view `notes`, `goals`, `calendar`, va login form trong sidebar.
- `useAuth.js`: luu token vao `localStorage`, giu `user` trong memory.
- `api.js`: dung `fetch`, base URL mac dinh `http://127.0.0.1:8000/api`.
- `NotesView.vue`: list/create/edit/delete notes.
- `GoalsView.vue`: list/create/edit/delete goals.
- `CalendarView.vue`: placeholder, chua goi API calendar.

Luu y chat luong hien tai:

- Mot so chu tieng Viet trong Vue template dang bi mojibake.
- Frontend chua co route guard theo auth; khi chua login van co the bam load API va nhan loi.
- `api.js` co ham update/delete notes/goals, nhung `export default` cuoi file chua export het cac ham nay.
- Chua co UI cho goal entries.
- Calendar API da co backend summary nhung frontend chua tich hop.

## CI/CD va ha tang

Workflow `.github/workflows/ci.yml` da ton tai:

- Job `file-change` dung `dorny/paths-filter`.
- Job backend chay khi `backend/**` thay doi.
- Job frontend test chay khi `frontend/**` thay doi.
- Job detect Vue mess.
- Job infra dung `pipery-terraform-ci`.

Rui ro workflow hien tai:

- Backend job container khai bao `node:20-bookworm-slim` nhung lai setup PHP va composer; can kiem tra lai working directory.
- Backend job dung `.env.ci`, `phpstan`, `php artisan insights`, nhung repo hien chua thay cac config/dependency tuong ung trong danh sach file quet.
- Frontend job chay `npm ci`, `npm run lint`, `npm run type-check`, `npm run test:unit`, nhung `frontend/package.json` hien chi co `dev`, `build`, `preview`.
- `actions/setup-node` cache npm mac dinh co the can `cache-dependency-path: frontend/package-lock.json` neu working directory nam trong `frontend`.
- Docker Compose hien chua expose ports cho `backend` va `frontend`, nen dung container de truy cap tu host co the chua du.

## Ket luan trang thai

Du an da vuot qua skeleton ban dau: auth, notes, goals va mot phan calendar backend da co. Tuy nhien tai lieu phan tich goc van mo ta nhieu mo hinh du kien phuc tap hon code that, dac biet la goal tracking, API response format, calendar day/week, tags, status, CI scripts va schema.
