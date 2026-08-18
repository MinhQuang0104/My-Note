---
name: testing-and-quality
description: Use when adding, fixing, or evaluating tests, validation commands, Laravel PHPUnit behavior, frontend build checks, or quality gates.
---

# Purpose

Provide realistic quality verification for this repository without inventing unavailable tooling.

# When to use

- A task asks for tests, coverage, validation, CI failures, QA, lint, format, or build verification.
- A code change affects backend behavior, frontend behavior, or CI quality gates.

# When NOT to use

- Pure documentation changes where Markdown/path validation is enough.
- MCP recommendation tasks with no code validation.

# Project-specific knowledge

- Backend test command: `cd backend && php artisan test` or `cd backend && composer run test`.
- PHPUnit suites are configured in `backend/phpunit.xml`.
- Backend tests currently use SQLite `:memory:`.
- Existing tests are placeholder example tests.
- Frontend has `dev`, `build`, and `preview` scripts only.
- CI references frontend lint/type/unit scripts not present in `frontend/package.json`.
- Backend has Laravel Pint as a dev dependency.

# Workflow

1. Determine which area changed: backend, frontend, CI, docs, infra.
2. Inspect existing tests before adding new ones.
3. For backend behavior, prefer Feature tests for API routes and authorization.
4. For model-only logic, use Unit tests only when no Laravel app behavior is needed.
5. For frontend work, run `npm run build`; do not claim lint/unit coverage unless scripts exist.
6. Report missing tools as gaps, not failures of the feature.

# Rules

- Do not add broad test frameworks casually.
- Do not change CI to require scripts that package files do not define.
- Do not rely only on placeholder tests for meaningful backend changes.

# Verification

- Backend: `cd backend && php artisan test`.
- Backend formatting: `cd backend && ./vendor/bin/pint --test` or `./vendor/bin/pint`.
- Frontend: `cd frontend && npm run build`.
- Docs/agent config: verify expected files and Markdown/front matter structure.

# Failure handling

- If dependencies are missing, identify the install command but ask before network-dependent installation when approval is required.
- If tests fail for unrelated existing reasons, summarize the failure and avoid modifying unrelated code.
