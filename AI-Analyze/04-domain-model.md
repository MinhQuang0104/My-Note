# 04. Domain Model

## Các khái niệm chính

### User

Đại diện cho người dùng sở hữu notes, goals, events.

MVP local có thể chưa cần User. Khi deploy public nên thêm User để bảo vệ dữ liệu.

### Note

Ghi chú cá nhân.

Thuộc tính gợi ý:

- id
- user_id
- title
- content
- created_at
- updated_at
- archived_at

### Goal

Định nghĩa mục tiêu/thói quen cần theo dõi.

Thuộc tính gợi ý:

- id
- user_id
- name
- description
- type: boolean | numeric | frequency
- target_value
- unit
- repeat_rule: daily | weekly | monthly | custom
- start_date
- end_date
- color
- icon
- is_active
- created_at
- updated_at

### GoalLog

Bản ghi tiến độ của một goal trong một ngày hoặc một mốc thời gian.

Thuộc tính gợi ý:

- id
- goal_id
- user_id
- log_date
- value
- status: not_done | partial | completed
- note
- created_at
- updated_at

Lưu ý:

- Với boolean goal, value có thể là 1 khi done.
- Với numeric goal, value là tổng giá trị đã nhập trong ngày.
- Có thể cho phép nhiều lần nhập trong ngày bằng GoalEntry riêng, nhưng MVP có thể dùng một GoalLog/ngày/goal để đơn giản.

### CalendarEvent

Lịch trình/sự kiện trên lịch.

Thuộc tính gợi ý:

- id
- user_id
- title
- description
- start_at
- end_at
- all_day
- location
- color
- created_at
- updated_at

### CalendarDayView

Đây không nhất thiết là bảng database. Có thể là view/model tổng hợp:

- Date.
- Events trong ngày.
- Goals expected trong ngày.
- Goal logs của ngày.
- Notes liên quan trong ngày, để sau.

## Quan hệ dữ liệu

- User 1-n Note.
- User 1-n Goal.
- Goal 1-n GoalLog.
- User 1-n CalendarEvent.
- Calendar hiển thị dữ liệu tổng hợp từ Goal, GoalLog, CalendarEvent.

## Luồng nghiệp vụ Goal Tracking

### Tạo goal numeric daily

Ví dụ: "Uống nước mỗi ngày"

- type = numeric
- target_value = 2
- unit = "lít"
- repeat_rule = daily

### Cập nhật tiến độ

Người dùng nhập 1 lít vào ngày 2026-08-06.

Hệ thống:

- Tìm GoalLog của goal + ngày.
- Nếu chưa có, tạo mới value = 1.
- Nếu đã có, tùy UX quyết định:
  - Ghi đè value mới; hoặc
  - Cộng dồn value mới vào value cũ.
- So sánh value với target_value.
- Nếu value >= target_value thì status = completed.
- Nếu 0 < value < target_value thì status = partial.
- Nếu value = 0 thì status = not_done.

Khuyến nghị MVP:

- Dùng cơ chế "set current total" để dễ sửa sai.
- Sau này thêm "quick add" để cộng dồn.

## Đề xuất schema logic ban đầu

```text
users
  id
  email
  password_hash
  created_at
  updated_at

notes
  id
  user_id
  title
  content
  created_at
  updated_at
  archived_at

goals
  id
  user_id
  name
  description
  type
  target_value
  unit
  repeat_rule
  start_date
  end_date
  color
  icon
  is_active
  created_at
  updated_at

goal_logs
  id
  goal_id
  user_id
  log_date
  value
  status
  note
  created_at
  updated_at

calendar_events
  id
  user_id
  title
  description
  start_at
  end_at
  all_day
  location
  color
  created_at
  updated_at
```

## Điểm cần cân nhắc

- Nếu chưa có auth, có thể bỏ `user_id` trong MVP local nhưng nên để kiến trúc sẵn sàng thêm User.
- Nếu muốn học backend nghiêm túc, nên có database thật thay vì chỉ localStorage. **Chốt DB: PostgreSQL** cho staging/prod; `SQLite` chấp nhận cho local dev nhưng test trên Postgres trước deploy.
- Nếu muốn deploy nhanh, có thể dùng managed database để giảm việc vận hành.

## Gợi ý tối ưu schema cho Postgres

- `notes.content` có thể lưu plain text hoặc `JSONB` nếu cần rich/editor metadata.
- Tạo index `(goal_id, log_date)` cho `goal_logs` để truy vấn theo ngày hiệu quả.
- Sử dụng `date` (không timestamp) cho `log_date` để dễ nhóm theo ngày và tránh timezone bugs; lưu timezone preferences ở user nếu cần.

