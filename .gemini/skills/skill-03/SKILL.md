---
name: auth-and-data-isolation
description: Use when a task touches authentication, authorization, Laravel Sanctum tokens, user-owned data access, or secret handling.
---

# Purpose

Protect user data boundaries and credential handling across the app.

# When to use

- A task mentions login, register, logout, bearer tokens, Sanctum, authorization, ownership, `403`, `401`, or secrets.
- A change affects Notes, Goals, Goal Entries, Calendar, or any user-scoped query.
- A task asks to inspect `.env`, credentials, tokens, or security-sensitive config.

# When NOT to use

- Pure styling changes with no data access.
- Local documentation changes that do not mention auth, users, credentials, or production access.

# Project-specific knowledge

- Public auth routes: `POST /api/auth/register`, `POST /api/auth/login`.
- Protected auth routes: `POST /api/auth/logout`, `GET /api/auth/me`.
- Protected resource routes use `auth:sanctum`.
- Tokens are created with `$user->createToken('local')->plainTextToken`.
- Frontend sends `Authorization: Bearer <token>`.
- `User` hides `password` and `remember_token` through attributes.
- Existing controllers manually check ownership with `abort_unless`.

# Workflow

1. Inspect auth routes, `AuthController`, affected resource controller, model relationships, and migrations.
2. Identify every read/write path that must be scoped to the authenticated user.
3. Ensure creates use `$request->user()->relationship()->create(...)` or equivalent.
4. Ensure reads, updates, and deletes verify ownership before returning data.
5. Do not print `.env` values. If environment inspection is needed, list variable names only.
6. Preserve password hashing and hidden attributes.

# Rules

- Never expose real secrets in output, docs, logs, examples, or generated configs.
- Never accept caller-provided `user_id` for owned resources.
- Default unknown auth cases to denied access.
- Do not disable Sanctum middleware to work around local testing issues.

# Verification

- Add or update backend Feature tests for cross-user denial where feasible.
- Run `cd backend && php artisan test`.
- Manually inspect JSON output paths for accidental user/token leakage.

# Failure handling

- If a requested feature conflicts with user isolation, explain the risk and propose a safer design.
- If token/session behavior is unclear, preserve the current bearer-token approach.
