gi# Project My Note Agent Guide

## Project Identity

Project My Note is a monorepo for a personal notes, goals, and calendar MVP.

For detailed domain concepts, architecture, and API contracts, refer to the documents in the `AI-Analyze/` directory. The agent should consult these on-demand for relevant tasks.

## Architecture

CONFIRMED architecture:

- Monorepo with separated frontend and backend applications.
- Frontend calls REST JSON APIs through `frontend/src/services/api.js`.
- Backend exposes REST routes in `backend/routes/api.php`.
- Protected APIs use `auth:sanctum`.
- Data access currently uses Laravel Eloquent models and relationships.
- Tests are Laravel PHPUnit unit/feature skeleton tests; frontend tests are not configured in `frontend/package.json`.

Dependency direction:

- Vue views and composables depend on `frontend/src/services/api.js`.
- API routes depend on controllers under `backend/app/Http/Controllers/Api`.
- Controllers depend directly on Eloquent models and Laravel validation/auth helpers.
- Models define relationships and fillable attributes.
- Migrations define the confirmed database schema.
- For deeper analysis, refer to `AI-Analyze/08-architecture-decision.md`.
Do not assume Clean Architecture, DDD, CQRS, queues, events, or microservices unless new code proves them.

## Technology Stack

CONFIRMED:

- PHP `^8.3`
- Laravel `^13.8`
- Laravel Sanctum `^4.3`
- PHPUnit `^12.5`
- Laravel Pint `^1.27`
- Vue `^3.5`
- Vite `^8`
- JavaScript ES modules
- Node 22 for local frontend Docker and frontend CI target
- Postgres 15 local Docker service
- SQLite in-memory database for current PHPUnit config
- GitHub Actions CI in `.github/workflows/`
- Docker Compose for local development

INFERRED:

- Production/staging database is intended to be Postgres based on `AI-Analyze` plans and Docker Compose.
- Terraform is planned but not implemented beyond documentation skeleton in `infra/`.
- AWS deployment is planned in `AI-Analyze/12-ci-cd-aws-plan.md`, but no deployable AWS or Terraform resources exist.

UNKNOWN:

- Production hosting provider.
- Secrets manager.
- Monitoring, metrics, tracing, alerting, or incident process.
- Release strategy beyond GitHub Actions on `main`.

## Coding Conventions

- Respect `backend/.editorconfig`: UTF-8, LF, 4-space indentation generally, 2-space YAML, final newline, trim trailing whitespace.
- Backend code uses Laravel controllers, request validation inline with `$request->validate(...)` or `Validator::make(...)`.
- Backend ownership checks use `abort_unless(..., 403)` before showing/updating/deleting user-owned resources.
- Backend responses currently return raw JSON resources or arrays, not the planned standard `{ success, data, error }` envelope from `AI-Analyze/09-api-contract.md`.
- Eloquent models use relationship methods.
- Frontend uses Vue `<script setup>`, Composition API refs, and direct service calls.
- Frontend auth token is stored in `localStorage` via `frontend/src/composables/useAuth.js`.
- Existing UI text includes Vietnamese labels, but some files appear mojibake-encoded. Preserve existing encoding and copy style unless explicitly asked to repair text.

## Development Workflow

Confirmed commands:

- Frontend install: `cd frontend && npm install`
- Frontend dev: `cd frontend && npm run dev`
- Frontend build: `cd frontend && npm run build`
- Frontend preview: `cd frontend && npm run preview`
- Backend setup script: `cd backend && composer run setup`
- Backend dev script: `cd backend && composer run dev`
- Backend test script: `cd backend && composer run test`
- Backend direct tests: `cd backend && php artisan test`
- Backend formatting: `cd backend && ./vendor/bin/pint`
- Local Docker services: `docker compose up`

CI workflow caveats:
- The CI workflow in `.github/workflows/ci.yml` has known issues (e.g., missing `working-directory`, missing npm scripts). The agent should validate CI commands against `package.json` and `composer.json` before suggesting changes.
- `.github/workflows/ci.yml` runs only when path filters detect changes under `frontend/**`, `backend/**`, or `infra/**`.
- The frontend CI job references `npm run lint`, `npm run type-check`, and `npm run test:unit`, but those scripts are not present in `frontend/package.json`.
- The frontend CI job does not set `working-directory: frontend`, so root-level `npm ci` is likely to fail unless the workflow is changed.
- The backend CI job references `.env.ci` and runs Composer/Laravel commands without `working-directory: backend`; no root Composer project was observed.
- Treat CI as a target design with likely implementation gaps, not as confirmed green automation.

## Testing

Current confirmed strategy:

- Backend PHPUnit suites: `backend/tests/Unit` and `backend/tests/Feature`.
- PHPUnit uses SQLite in-memory database and array cache/session/mail in `backend/phpunit.xml`.
- Existing tests are Laravel example tests only.
- No confirmed frontend test runner, lint script, or type-check script exists in `frontend/package.json`.

When changing backend behavior, add or update focused Feature tests when feasible. When changing frontend behavior, at minimum run `npm run build` because no frontend test script is configured.

## Security

- Never print values from `.env`, tokens, passwords, app keys, database passwords, or API keys.
- `backend/.env` exists and may contain local secrets; inspect only keys/names if needed, not values.
- All user-owned API resources must stay scoped to `auth()->id()` or `$request->user()`.
- Do not accept `user_id` from frontend payloads for notes, goals, or goal entries.
- Preserve password hashing through Laravel hashing/casts.
- Do not weaken Sanctum authentication or expose protected routes.
- Docker Compose uses local development database credentials; do not reuse them for production docs except as local examples.

## Agent Behavior

- Use the 10 configured skills to handle specific tasks. Activate the most relevant skill based on its description.
- Inspect relevant files before modifying anything.
- Make narrowly scoped changes that match existing Laravel and Vue patterns.
- Do not modify application source code when the task is only agent configuration or documentation.
- Mark unsupported claims as `UNKNOWN` or `INFERRED`.
- Ask before destructive operations, database resets, production changes, or pushing/committing.
- Prefer least privilege for MCPs, CI credentials, database access, and cloud access.
- Verify with the smallest safe command that covers the touched area.

## Definition of Done

Before finishing a task:

- Relevant files were inspected.
- Changes follow confirmed project patterns.
- User-owned data isolation is preserved for backend changes.
- No secrets are printed or committed.
- Appropriate safe validation command was run, or the reason it could not run is stated.
- Documentation distinguishes confirmed facts from inferred plans and unknowns.
