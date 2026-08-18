---
name: project-code-review-and-debugging
description: Use when reviewing changes, diagnosing bugs, tracing frontend-backend failures, or investigating common local/CI failure modes.
---

# Purpose

Provide a project-specific debugging and review workflow across the monorepo.

# When to use

- A task asks for review, bug diagnosis, failing tests, API failures, auth failures, build failures, or unexpected UI behavior.
- A task spans frontend, backend, Docker, and CI boundaries.

# When NOT to use

- Greenfield implementation where no failure or review is involved.
- Pure documentation generation.

# Project-specific knowledge

Common failure areas:

- Missing or invalid Sanctum bearer token returns protected API failures.
- Frontend default API base points to `http://127.0.0.1:8000/api`.
- Docker backend service is `php:8.3-fpm` and may not itself expose Laravel's `artisan serve`.
- CI commands may run from the wrong directory.
- Frontend CI references missing scripts.
- Backend tests use SQLite while local Docker uses Postgres.
- Calendar API implementation is simpler than planning docs.

# Workflow

1. Reproduce or inspect the exact failing path.
2. Trace from frontend view to `api.js`, then to Laravel route, controller, model, and migration.
3. Check authentication state and ownership checks for `401`/`403` failures.
4. Check request payload names against controller validation and migrations.
5. For CI failures, compare each command with the manifest in the intended subdirectory.
6. Present review findings first, ordered by severity, with file/line references where possible.

# Rules

- Do not hide existing repository issues by loosening auth or validation.
- Do not modify unrelated files during debugging.
- Do not print secret values while investigating config.
- Avoid broad refactors unless they directly fix the failure.

# Verification

- Use focused commands: backend tests, frontend build, YAML validation, or Docker Compose config depending on the failure.
- Re-run the failing command after a fix when feasible.

# Failure handling

- If a failure cannot be reproduced locally, document the evidence, likely cause, and next diagnostic command.
- If the root cause is an existing config mismatch, report it separately from the requested code change.
