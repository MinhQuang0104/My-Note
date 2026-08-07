# 10. API Implementation Plan (Backend Laravel)

## 1. Overview

- Backend sẽ dùng Laravel để phục vụ REST API.
- Mỗi API endpoint trả về JSON theo standard format đã định nghĩa.
- Authentication: Laravel Sanctum (SPA + token-based auth).
- Authorization: kiểm tra quyền truy cập tài nguyên theo `user_id`.
- Local dev dùng Docker Postgres 15.
- Tính năng chính: Notes, Goals, Goal Entries, Calendar, Events, Users.

## 2. Authentication & Authorization

### Authentication

- Sử dụng `Laravel Sanctum` cho SPA.
- Endpoint đăng ký: `POST /api/auth/register`.
- Endpoint đăng nhập: `POST /api/auth/login`.
- Endpoint logout: `POST /api/auth/logout`.
- Endpoint user hiện tại: `GET /api/auth/me`.

### Authorization

- Các route dữ liệu phải yêu cầu auth middleware.
- Tài nguyên chỉ trả/cho phép thao tác nếu `resource.user_id === auth()->id()`.
- Nếu user truy cập tài nguyên khác, trả `403 Forbidden`.
- Dùng Policy/authorize trong Laravel để tách logic authorization.

## 3. Routes

### Auth

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`

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
- `DELETE /api/goals/{id}`
- `PATCH /api/goals/{id}/disable`

### Goal Entries

- `GET /api/goals/{goalId}/entries`
- `POST /api/goals/{goalId}/entries`
- `PUT /api/goals/{goalId}/entries/{entryId}`
- `DELETE /api/goals/{goalId}/entries/{entryId}`

### Calendar

- `GET /api/calendar/day?date=YYYY-MM-DD`
- `GET /api/calendar/week?start_date=YYYY-MM-DD`

### Events (Calendar Events)

- `GET /api/events`
- `POST /api/events`
- `GET /api/events/{id}`
- `PUT /api/events/{id}`
- `DELETE /api/events/{id}`

## 4. Controllers

- `AuthController`
- `NoteController`
- `GoalController`
- `GoalEntryController`
- `CalendarController`
- `EventController`

### Controller responsibilities

- Chuyển request vào Request validation.
- Gọi service/domain hoặc model để xử lý.
- Trả về response theo format chuẩn.
- Xử lý exceptions và trả lỗi rõ ràng.

## 5. Request Validation

Tạo Request classes cho mỗi endpoint:

- `RegisterRequest`
- `LoginRequest`
- `StoreNoteRequest`, `UpdateNoteRequest`
- `StoreGoalRequest`, `UpdateGoalRequest`
- `StoreGoalEntryRequest`, `UpdateGoalEntryRequest`
- `StoreEventRequest`, `UpdateEventRequest`
- `CalendarDayRequest`, `CalendarWeekRequest`

### Validation rules mẫu

- Notes:
  - `title`: required|string|max:255
  - `content`: nullable|string
  - `tags`: nullable|array
  - `tags.*`: string|max:50
  - `is_archived`: boolean

- Goals:
  - `name`: required|string|max:255
  - `description`: nullable|string
  - `type`: required|in:boolean,numeric,frequency
  - `target_value`: required_if:type,numeric|numeric|min:0
  - `unit`: nullable|string|max:50
  - `repeat_rule`: required|in:daily,weekly,monthly,custom
  - `start_date`: required|date
  - `end_date`: nullable|date|after_or_equal:start_date
  - `is_active`: boolean

- Goal Entries:
  - `log_date`: required|date
  - `value`: required|numeric|min:0
  - `note`: nullable|string

- Events:
  - `title`: required|string|max:255
  - `description`: nullable|string
  - `start_at`: required|date_format:Y-m-d\TH:i:sP
  - `end_at`: required|date_format:Y-m-d\TH:i:sP|after_or_equal:start_at
  - `all_day`: boolean
  - `location`: nullable|string|max:255
  - `color`: nullable|string|max:20

- Calendar queries:
  - `date`: required|date
  - `start_date`: required|date

## 6. Resources / Response Transformation

- `NoteResource`
- `GoalResource`
- `GoalEntryResource`
- `EventResource`
- `CalendarDayResource`
- `CalendarWeekResource`

### Resource responsibilities

- Định dạng field trả về như `excerpt`, `status`, `progress`.
- Ẩn dữ liệu internal như `user_id`.
- Trả `created_at`/`updated_at` ở định dạng ISO 8601 UTC.

## 7. Models & Relationships

- `User` hasMany `Note`
- `User` hasMany `Goal`
- `User` hasMany `Event`
- `Goal` belongsTo `User`
- `Goal` hasMany `GoalEntry`
- `GoalEntry` belongsTo `Goal`
- `GoalEntry` belongsTo `User`

### Field-level behaviors

- `GoalEntry` tính `status` dựa trên tổng `value` của một ngày `log_date`.
- `CalendarController` tổng hợp data từ `notes`, `goals`, `goal_entries`, `events`.

## 8. Business Logic

### Goal entries

- Mỗi `GoalEntry` là một event append.
- Khi list entries theo ngày, backend tổng hợp giá trị của cùng `goal_id` + `log_date`.
- Trạng thái mỗi ngày tính bằng:
  - `not_done` nếu không có entry.
  - `partial` nếu tổng value < target_value.
  - `completed` nếu tổng value >= target_value.

### Calendar

- `calendar/day` trả chi tiết:
  - notes cho ngày
  - goals có trạng thái và entries
  - events trong ngày
- `calendar/week` trả summary mỗi ngày:
  - `note_count`, `goal_status`, `completed_goal_count`, `pending_goal_count`, `event_count`

### Authorization

- Dùng Policy để đảm bảo user chỉ thao tác trên tài nguyên của mình.
- Controller gọi `authorize()` hoặc `Gate::authorize()` trước khi truy xuất.

## 9. Error Handling

- Sử dụng middleware `HandleApiExceptions` nếu cần để chuẩn hoá JSON error.
- Ràng buộc validation trả `422` với `VALIDATION_FAILED`.
- Auth trả `401`.
- Forbidden trả `403`.
- Resource not found trả `404`.
- Server error trả `500` với mã `INTERNAL_ERROR`.

## 10. Minimal testing plan

- Test controllers cơ bản:
  - Notes CRUD
  - Goals CRUD
  - Goal entry append
  - Calendar day/week response structure
- Test business logic:
  - status calculation với goal entries
  - policy authorization
  - validation rules

---

## Implementation sequence

1. Tạo Laravel project trong `backend/`.
2. Thiết lập Sanctum và route auth.
3. Viết migration schema Postgres.
4. Viết Models + relationships.
5. Viết Requests + Controllers cho Notes.
6. Tiếp tục Goals + Goal Entries.
7. Viết CalendarController.
8. Viết Resources và response format.
9. Viết GitHub Actions skeleton.
10. Chạy local với Docker Compose và Postgres.
