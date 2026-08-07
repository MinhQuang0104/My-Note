# 11. PostgreSQL Database Schema & Migration Outline

## PostgreSQL version

- Chốt dùng **PostgreSQL 15** cho local và production.

## Schema overview

### users

- `id`: bigserial primary key
- `name`: varchar(255)
- `email`: varchar(255) unique not null
- `email_verified_at`: timestamp with time zone null
- `password`: varchar(255) not null
- `remember_token`: varchar(100) null
- `created_at`: timestamp with time zone not null
- `updated_at`: timestamp with time zone not null

Indexes:
- unique index on `email`

### notes

- `id`: bigserial primary key
- `user_id`: bigint not null references `users(id)` on delete cascade
- `title`: varchar(255) not null
- `content`: text null
- `tags`: jsonb null
- `is_archived`: boolean not null default false
- `created_at`: timestamp with time zone not null
- `updated_at`: timestamp with time zone not null

Indexes:
- index on `(user_id, updated_at)`
- GIN index on `tags`

### goals

- `id`: bigserial primary key
- `user_id`: bigint not null references `users(id)` on delete cascade
- `name`: varchar(255) not null
- `description`: text null
- `type`: varchar(20) not null check in (`boolean`, `numeric`, `frequency`)
- `target_value`: numeric null
- `unit`: varchar(50) null
- `repeat_rule`: varchar(20) not null check in (`daily`, `weekly`, `monthly`, `custom`)
- `start_date`: date not null
- `end_date`: date null
- `is_active`: boolean not null default true
- `color`: varchar(20) null
- `icon`: varchar(64) null
- `created_at`: timestamp with time zone not null
- `updated_at`: timestamp with time zone not null

Indexes:
- index on `(user_id, is_active)`
- index on `(user_id, start_date)`

### goal_entries

- `id`: bigserial primary key
- `goal_id`: bigint not null references `goals(id)` on delete cascade
- `user_id`: bigint not null references `users(id)` on delete cascade
- `log_date`: date not null
- `value`: numeric not null default 0
- `note`: text null
- `status`: varchar(20) not null check in (`not_done`, `partial`, `completed`)
- `created_at`: timestamp with time zone not null
- `updated_at`: timestamp with time zone not null

Indexes:
- unique index on `(goal_id, user_id, log_date, id)` optional if dedup logic needed
- index on `(goal_id, log_date)`
- index on `(user_id, log_date)`

### calendar_events

- `id`: bigserial primary key
- `user_id`: bigint not null references `users(id)` on delete cascade
- `title`: varchar(255) not null
- `description`: text null
- `start_at`: timestamp with time zone not null
- `end_at`: timestamp with time zone not null
- `all_day`: boolean not null default false
- `location`: varchar(255) null
- `color`: varchar(20) null
- `created_at`: timestamp with time zone not null
- `updated_at`: timestamp with time zone not null

Indexes:
- index on `(user_id, start_at)`
- index on `(user_id, end_at)`

## Notes on data modeling

- `goal_entries.status` có thể cập nhật khi entry tạo/sửa/xóa để tiết kiệm truy vấn.
- `log_date` dùng kiểu `date` để tránh timezone drift trong goal tracking.
- `notes.tags` dùng `jsonb` để dễ filter và mở rộng về sau.
- `calendar_events.start_at` và `end_at` dùng `timestamptz` để hỗ trợ UTC.

## Migration outline (Laravel)

### 1. Create users table

- `php artisan make:migration create_users_table`
- Mặc định Laravel đã có migration chuẩn cho `users`.

### 2. Create notes table

- `php artisan make:migration create_notes_table`
- Schema fields:
  - `foreignId('user_id')->constrained()->cascadeOnDelete()`
  - `string('title')`
  - `text('content')->nullable()`
  - `jsonb('tags')->nullable()`
  - `boolean('is_archived')->default(false)`
  - timestamps()
- Indexes:
  - `index(['user_id', 'updated_at'])`
  - `gin('tags')`

### 3. Create goals table

- `php artisan make:migration create_goals_table`
- Schema fields:
  - `foreignId('user_id')->constrained()->cascadeOnDelete()`
  - `string('name')`
  - `text('description')->nullable()`
  - `string('type', 20)`
  - `decimal('target_value', 10, 2)->nullable()`
  - `string('unit', 50)->nullable()`
  - `string('repeat_rule', 20)`
  - `date('start_date')`
  - `date('end_date')->nullable()`
  - `boolean('is_active')->default(true)`
  - `string('color', 20)->nullable()`
  - `string('icon', 64)->nullable()`
  - timestamps()
- Add check constraints via raw SQL if desired.
- Indexes:
  - `index(['user_id', 'is_active'])`
  - `index(['user_id', 'start_date'])`

### 4. Create goal_entries table

- `php artisan make:migration create_goal_entries_table`
- Schema fields:
  - `foreignId('goal_id')->constrained()->cascadeOnDelete()`
  - `foreignId('user_id')->constrained()->cascadeOnDelete()`
  - `date('log_date')`
  - `decimal('value', 10, 2)`
  - `text('note')->nullable()`
  - `string('status', 20)`
  - timestamps()
- Indexes:
  - `index(['goal_id', 'log_date'])`
  - `index(['user_id', 'log_date'])`

### 5. Create calendar_events table

- `php artisan make:migration create_calendar_events_table`
- Schema fields:
  - `foreignId('user_id')->constrained()->cascadeOnDelete()`
  - `string('title')`
  - `text('description')->nullable()`
  - `timestampTz('start_at')`
  - `timestampTz('end_at')`
  - `boolean('all_day')->default(false)`
  - `string('location', 255)->nullable()`
  - `string('color', 20)->nullable()`
  - timestamps()
- Indexes:
  - `index(['user_id', 'start_at'])`

## Suggested migration order

1. `create_users_table`
2. `create_notes_table`
3. `create_goals_table`
4. `create_goal_entries_table`
5. `create_calendar_events_table`

## Local Docker Postgres

- Dùng image `postgres:15`.
- Mount volume cho dữ liệu.
- Connection config trong `.env`.
- Sử dụng `DB_CONNECTION=pgsql`, `DB_HOST=db`, `DB_PORT=5432`, `DB_DATABASE=my_note`, `DB_USERNAME=postgres`, `DB_PASSWORD=secret`.
