---
name: local-docker-dev-environment
description: Use when working with docker-compose, local Postgres, containerized frontend/backend development, or Docker documentation.
---

# Purpose

Guide safe local container and database environment work.

# When to use

- A task touches `docker-compose.yml` or `infra/docker`.
- A task mentions Docker, Compose, local Postgres, containers, ports, health checks, or local environment setup.

# When NOT to use

- Pure Laravel or Vue code changes that do not involve container behavior.
- Production infrastructure or Terraform tasks unless Docker is part of the request.

# Project-specific knowledge

- `docker-compose.yml` defines `db`, `backend`, and `frontend`.
- `db` uses `postgres:15-alpine`, database `my_note`, user `postgres`, local password `postgres`, and a named volume.
- `db` has a `pg_isready` health check.
- `backend` uses `php:8.3-fpm`, mounts `./backend:/var/www`, and depends on healthy db.
- `frontend` uses `node:22`, mounts `./frontend:/app`, and runs `npm install && npm run dev -- --host 0.0.0.0`.
- `infra/docker/README.md` is a short local-container note.

# Workflow

1. Inspect `docker-compose.yml`, backend/frontend manifests, and relevant env examples.
2. Determine whether the change is for local dev only or deployment.
3. Keep local credentials clearly marked as development-only.
4. Avoid deleting named volumes unless the user explicitly requests data reset.
5. Ensure service commands match available scripts.
6. Document any required `.env` values without real secrets.

# Rules

- Do not run destructive Docker volume removal without explicit approval.
- Do not promote local Postgres credentials to production guidance.
- Do not assume Nginx, queue workers, or production PHP-FPM wiring beyond what Compose shows.

# Verification

- Validate Compose structure with `docker compose config` if Docker is available.
- For frontend command changes, run or check `cd frontend && npm run build`.
- For backend DB changes, run backend tests when possible.

# Failure handling

- If Docker is unavailable, inspect files statically and report that runtime validation was not performed.
- If backend container cannot serve HTTP because it is only PHP-FPM, state that and propose a dev-server-compatible setup.
