# 09. API Contract

## Tổng quan

- App mở cho nhiều user.
- Cần authentication/authorization.
- Local dev dùng Docker Postgres (PostgreSQL 15).
- Calendar cần hỗ trợ day/week view.
- Goal tracking flow dùng "append entry".
- UI cần có màn hình cho Notes, Goals, Calendar.
- API chuẩn REST JSON.
- Standard response/error format dùng chung.

---

## Authentication

- `Authorization: Bearer <token>`
- Mỗi request phải xác thực để truy vấn/ghi dữ liệu của user hiện tại.
- `user_id` không đưa lên request body cho tài nguyên thuộc user.

### Header mẫu

```
Authorization: Bearer eyJhbGciOi...
Content-Type: application/json
```

---

## Standard response format

### Success

```json
{
  "success": true,
  "message": "Optional human-readable message",
  "data": ...,            
  "meta": { ... }         
}
```

- `success`: boolean.
- `message`: optional string, hữu ích cho UX toast.
- `data`: object hoặc array.
- `meta`: optional metadata pagination, summary, hoặc pagination links.

### Error

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "Validation failed for the request.",
    "details": {
      "title": ["Title is required."],
      "due_date": ["Due date must be a valid date."]
    }
  }
}
```

- `code`: string identifier, dùng cho frontend logic.
- `message`: human-readable.
- `details`: optional object, dùng với validation errors hoặc business errors.

---

## Notes API

### Create note

- `POST /api/notes`
- Request body:
  - `title`: string, required.
  - `content`: string, optional.
  - `tags`: array[string], optional.
  - `is_archived`: boolean, optional, default false.

```json
{
  "title": "Review weekly goals",
  "content": "Today I want to update the goal progress and plan next week.",
  "tags": ["weekly", "planning"],
  "is_archived": false
}
```

- Response `201 Created`

```json
{
  "success": true,
  "data": {
    "id": 123,
    "title": "Review weekly goals",
    "content": "Today I want to update the goal progress and plan next week.",
    "tags": ["weekly", "planning"],
    "is_archived": false,
    "created_at": "2026-08-06T12:00:00Z",
    "updated_at": "2026-08-06T12:00:00Z"
  }
}
```

### List notes

- `GET /api/notes`
- Query params:
  - `q`: string, optional, search title/content.
  - `archived`: boolean, optional.
  - `page`: integer, optional.
  - `per_page`: integer, optional.

- Response `200 OK`

```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "title": "Review weekly goals",
      "excerpt": "Today I want to update the goal progress...",
      "tags": ["weekly", "planning"],
      "is_archived": false,
      "created_at": "2026-08-06T12:00:00Z",
      "updated_at": "2026-08-06T12:00:00Z"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 12
  }
}
```

### Get note detail

- `GET /api/notes/{id}`

- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "id": 123,
    "title": "Review weekly goals",
    "content": "Today I want to update the goal progress and plan next week.",
    "tags": ["weekly", "planning"],
    "is_archived": false,
    "created_at": "2026-08-06T12:00:00Z",
    "updated_at": "2026-08-06T12:00:00Z"
  }
}
```

### Update note

- `PUT /api/notes/{id}`
- Request body: same fields như create, tất cả optional.

```json
{
  "title": "Review weekly goals (updated)",
  "content": "Updated note content.",
  "tags": ["weekly"],
  "is_archived": false
}
```

- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "id": 123,
    "title": "Review weekly goals (updated)",
    "content": "Updated note content.",
    "tags": ["weekly"],
    "is_archived": false,
    "created_at": "2026-08-06T12:00:00Z",
    "updated_at": "2026-08-06T12:05:00Z"
  }
}
```

### Delete note

- `DELETE /api/notes/{id}`
- Response `200 OK`

```json
{
  "success": true,
  "message": "Note deleted successfully."
}
```

---

## Goals API

### Goal model

- `id`
- `user_id`
- `name`
- `description`
- `type`: `boolean` | `numeric` | `frequency`
- `target_value`: numeric, optional for boolean.
- `unit`: string.
- `repeat_rule`: `daily` | `weekly` | `monthly` | `custom`
- `start_date`: date.
- `end_date`: date, optional.
- `is_active`: boolean.
- `color`: optional string.
- `icon`: optional string.
- `tags`: array[string], optional.
- `created_at`, `updated_at`

### Create goal

- `POST /api/goals`
- Request body:

```json
{
  "name": "Drink water",
  "description": "Drink at least 2 liters daily.",
  "type": "numeric",
  "target_value": 2,
  "unit": "liters",
  "repeat_rule": "daily",
  "start_date": "2026-08-06",
  "end_date": null,
  "is_active": true,
  "color": "#4CAF50",
  "icon": "water"
}
```

- Response `201 Created`

```json
{
  "success": true,
  "data": {
    "id": 45,
    "name": "Drink water",
    "description": "Drink at least 2 liters daily.",
    "type": "numeric",
    "target_value": 2,
    "unit": "liters",
    "repeat_rule": "daily",
    "start_date": "2026-08-06",
    "end_date": null,
    "is_active": true,
    "color": "#4CAF50",
    "icon": "water",
    "created_at": "2026-08-06T12:00:00Z",
    "updated_at": "2026-08-06T12:00:00Z"
  }
}
```

### List goals

- `GET /api/goals`
- Query params:
  - `active`: boolean, optional.
  - `repeat_rule`: string, optional.
  - `page`, `per_page`: optional.

- Response `200 OK`

```json
{
  "success": true,
  "data": [
    {
      "id": 45,
      "name": "Drink water",
      "description": "Drink at least 2 liters daily.",
      "type": "numeric",
      "target_value": 2,
      "unit": "liters",
      "repeat_rule": "daily",
      "start_date": "2026-08-06",
      "end_date": null,
      "is_active": true,
      "color": "#4CAF50",
      "icon": "water",
      "created_at": "2026-08-06T12:00:00Z",
      "updated_at": "2026-08-06T12:00:00Z"
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 5
  }
}
```

### Get goal detail

- `GET /api/goals/{id}`
- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "id": 45,
    "name": "Drink water",
    "description": "Drink at least 2 liters daily.",
    "type": "numeric",
    "target_value": 2,
    "unit": "liters",
    "repeat_rule": "daily",
    "start_date": "2026-08-06",
    "end_date": null,
    "is_active": true,
    "color": "#4CAF50",
    "icon": "water",
    "created_at": "2026-08-06T12:00:00Z",
    "updated_at": "2026-08-06T12:00:00Z",
    "progress_summary": {
      "current_streak": 3,
      "last_completed": "2026-08-05",
      "total_entries": 8
    }
  }
}
```

### Update goal

- `PUT /api/goals/{id}`
- Request body: same fields như create, tất cả optional.

```json
{
  "description": "Drink at least 2 liters every day.",
  "target_value": 2.5
}
```

- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "id": 45,
    "name": "Drink water",
    "description": "Drink at least 2 liters every day.",
    "type": "numeric",
    "target_value": 2.5,
    "unit": "liters",
    "repeat_rule": "daily",
    "start_date": "2026-08-06",
    "end_date": null,
    "is_active": true,
    "color": "#4CAF50",
    "icon": "water",
    "created_at": "2026-08-06T12:00:00Z",
    "updated_at": "2026-08-06T12:10:00Z"
  }
}
```

### Delete/disable goal

- `DELETE /api/goals/{id}` hoặc `PATCH /api/goals/{id}/disable`
- Response `200 OK`

```json
{
  "success": true,
  "message": "Goal disabled successfully."
}
```

### Goal entries (append entry)

- `POST /api/goals/{goalId}/entries`
- Request body:
  - `log_date`: date, required.
  - `value`: numeric or boolean.
  - `note`: string, optional.

```json
{
  "log_date": "2026-08-06",
  "value": 0.5,
  "note": "Drank 500ml before lunch."
}
```

- Response `201 Created`

```json
{
  "success": true,
  "data": {
    "id": 210,
    "goal_id": 45,
    "log_date": "2026-08-06",
    "value": 0.5,
    "status": "partial",
    "note": "Drank 500ml before lunch.",
    "created_at": "2026-08-06T12:15:00Z",
    "updated_at": "2026-08-06T12:15:00Z"
  }
}
```

### List goal entries for a goal

- `GET /api/goals/{goalId}/entries`
- Query params:
  - `start_date`: optional.
  - `end_date`: optional.

- Response `200 OK`

```json
{
  "success": true,
  "data": [
    {
      "id": 210,
      "goal_id": 45,
      "log_date": "2026-08-06",
      "value": 0.5,
      "status": "partial",
      "note": "Drank 500ml before lunch.",
      "created_at": "2026-08-06T12:15:00Z",
      "updated_at": "2026-08-06T12:15:00Z"
    }
  ]
}
```

### Update goal entry

- `PUT /api/goals/{goalId}/entries/{entryId}`
- Request body: `value`, `note`, optional.

```json
{
  "value": 1.0,
  "note": "Added another 500ml."
}
```

- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "id": 210,
    "goal_id": 45,
    "log_date": "2026-08-06",
    "value": 1.0,
    "status": "partial",
    "note": "Added another 500ml.",
    "created_at": "2026-08-06T12:15:00Z",
    "updated_at": "2026-08-06T12:20:00Z"
  }
}
```

### Delete goal entry

- `DELETE /api/goals/{goalId}/entries/{entryId}`
- Response `200 OK`

```json
{
  "success": true,
  "message": "Goal entry deleted successfully."
}
```

---

## Calendar API

Calendar responses tổng hợp dữ liệu notes/goals/events cho day/week view.

### Day view

- `GET /api/calendar/day?date=2026-08-06`
- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "date": "2026-08-06",
    "notes": [
      {
        "id": 123,
        "title": "Review weekly goals",
        "excerpt": "Today I want to update the goal progress...",
        "updated_at": "2026-08-06T12:00:00Z"
      }
    ],
    "goals": [
      {
        "id": 45,
        "name": "Drink water",
        "repeat_rule": "daily",
        "target_value": 2,
        "unit": "liters",
        "status": "partial",
        "progress": 1.0,
        "entries": [
          {
            "id": 210,
            "value": 0.5,
            "note": "Drank 500ml before lunch."
          }
        ]
      }
    ],
    "events": [
      {
        "id": 301,
        "title": "Gym session",
        "start_at": "2026-08-06T18:00:00Z",
        "end_at": "2026-08-06T19:00:00Z",
        "all_day": false,
        "color": "#2196F3"
      }
    ]
  }
}
```

### Week view

- `GET /api/calendar/week?start_date=2026-08-03`
- Response `200 OK`

```json
{
  "success": true,
  "data": {
    "start_date": "2026-08-03",
    "end_date": "2026-08-09",
    "days": [
      {
        "date": "2026-08-03",
        "note_count": 1,
        "goal_status": "mixed",
        "completed_goal_count": 1,
        "pending_goal_count": 2,
        "event_count": 1
      },
      {
        "date": "2026-08-04",
        "note_count": 0,
        "goal_status": "completed",
        "completed_goal_count": 2,
        "pending_goal_count": 0,
        "event_count": 0
      }
    ]
  }
}
```

#### Optional detail query for a week day

- `GET /api/calendar/day?date=2026-08-03` để lấy chi tiết ngày.

---

## Common error examples

### Validation failure

- `422 Unprocessable Entity`

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "Validation failed for the request.",
    "details": {
      "title": ["Title is required."],
      "type": ["Type must be one of boolean, numeric, frequency."]
    }
  }
}
```

### Unauthorized

- `401 Unauthorized`

```json
{
  "success": false,
  "error": {
    "code": "UNAUTHORIZED",
    "message": "Authentication credentials were missing or invalid."
  }
}
```

### Forbidden

- `403 Forbidden`

```json
{
  "success": false,
  "error": {
    "code": "FORBIDDEN",
    "message": "You do not have permission to access this resource."
  }
}
```

### Not found

- `404 Not Found`

```json
{
  "success": false,
  "error": {
    "code": "NOT_FOUND",
    "message": "Requested resource not found."
  }
}
```

### Server error

- `500 Internal Server Error`

```json
{
  "success": false,
  "error": {
    "code": "INTERNAL_ERROR",
    "message": "An unexpected error occurred. Please try again later."
  }
}
```

---

## Note về authorization

- Tài nguyên `notes`, `goals`, `goal entries`, và `calendar` luôn chỉ trả dữ liệu của user hiện tại.
- Nếu user cố truy cập tài nguyên khác, trả `403 Forbidden`.

## Data model note

### Goal entry status

- `not_done`: không có entry cho một ngày.
- `partial`: giá trị tổng nhỏ hơn target.
- `completed`: giá trị tổng đạt hoặc vượt target.

### Calendar data

- `calendar/day` trả chi tiết note/goal/event cho 1 ngày.
- `calendar/week` trả summary để UI hiển thị overview nhanh.
- timeline event chi tiết có thể lưu trong `events` nếu cần mở rộng.
