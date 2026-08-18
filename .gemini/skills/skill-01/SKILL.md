---
name: laravel-api-development
description: Use when implementing or changing Laravel REST API routes, controllers, validation, Eloquent models, or JSON responses in the backend application.
---

# Purpose

Help Gemini work safely inside the Laravel API backend for Project My Note.

# When to use

- A task mentions `backend/`, Laravel, routes, controllers, models, migrations, Sanctum APIs, or JSON endpoints.
- A task adds or changes Notes, Goals, Goal Entries, Calendar, or Auth API behavior.
- A frontend task requires backend endpoint changes.

# When NOT to use

- Pure frontend styling or Vue-only behavior with no API changes.
- Documentation-only tasks that do not alter backend behavior.
- Terraform, Docker, or CI-only changes.

# Project-specific knowledge

- API routes live in `backend/routes/api.php`.
- API controllers live in `backend/app/Http/Controllers/Api`.
- Confirmed resources: auth, notes, goals, nested goal entries, calendar aggregate.
- Protected APIs are grouped under `Route::middleware('auth:sanctum')`.
- Controllers currently return `response()->json(...)` with raw model arrays or message objects.
- Ownership is enforced with checks such as `abort_unless($note->user_id === auth()->id(), 403)`.
- Models use Eloquent relationships instead of repository classes.

# Workflow

1. Inspect `backend/routes/api.php`, the relevant controller, model, migration, and tests.
2. Confirm whether the requested behavior matches existing code or only planning docs in `AI-Analyze/`.
3. Add or change the route using Laravel's existing routing style.
4. Keep validation close to the controller unless the project introduces Form Requests.
5. Use `$request->user()` for creating user-owned records.
6. Enforce ownership before reading, updating, or deleting nested/user-owned resources.
7. Return JSON responses using the currently implemented response style unless the task explicitly asks to migrate to the planned envelope.
8. Add focused Feature tests when behavior changes.

# Rules

- Do not trust `user_id` from request bodies.
- Do not expose records belonging to another user.
- Do not invent service layers, DTOs, or repositories unless existing code starts using them or the task requires it.
- Preserve Sanctum protection for private resources.
- Keep route names and nested parameters aligned with Laravel route model binding.

# Verification

- Run `cd backend && php artisan test` after backend behavior changes when dependencies are available.
- Run `cd backend && ./vendor/bin/pint --test` or `./vendor/bin/pint` when formatting backend PHP changes.
- If tests cannot run, report the exact blocker.

# Failure handling

- If planning docs conflict with implemented routes/schema, state which source is implemented and ask before broad contract migrations.
- If an ownership rule is unclear, fail closed and require authenticated user scoping.
