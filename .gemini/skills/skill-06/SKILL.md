---
name: ci-cd-github-actions
description: Use when working on GitHub Actions, path filters, frontend/backend/infra quality jobs, CI scripts, or release/deployment automation.
---

# Purpose

Help maintain and correct the repository's GitHub Actions automation.

# When to use

- A task touches `.github/workflows/ci.yml`.
- A task mentions CI, GitHub Actions, pull requests, path filters, phpstan, PHP Insights, npm CI, Terraform CI, or deployment gates.

# When NOT to use

- Local-only code changes with no CI impact.
- Terraform implementation unless the task also changes CI.

# Project-specific knowledge

- CI runs on push and pull request to `main`.
- A `file-change` job uses `dorny/paths-filter@v4` to detect `frontend/**`, `backend/**`, and `infra/**`.
- Backend job uses a Postgres service and attempts PHP setup, Composer install, key generation, PHPStan, PHP Insights, and Laravel tests.
- Frontend job attempts Node 22.12, `npm ci`, lint, type-check, and unit tests.
- Frontend mess detection uses `brenoepics/vmd-action@v0.0.7`.
- Infra job uses `pipery-dev/pipery-terraform-ci@v1`.

# Known CI Risks

- Frontend job lacks confirmed `working-directory: frontend`.
- Frontend scripts `lint`, `type-check`, and `test:unit` are not defined.
- Backend job lacks confirmed `working-directory: backend`.
- `.env.ci` was not observed in the repository.
- Root package/composer files were not observed.

# Workflow

1. Inspect `.github/workflows/ci.yml` and affected package/composer files.
2. Align every CI command with the directory where its manifest exists.
3. Ensure scripts referenced in CI exist before requiring them.
4. Keep path filters accurate for monorepo boundaries.
5. Use least-privilege secrets and avoid printing secret values.
6. Separate validation from deployment unless deployment is explicitly requested.

# Rules

- Do not add production deploy steps without explicit user approval.
- Do not print `DB_PASSWORD`, `APP_KEY`, cloud keys, or token values in CI logs.
- Do not make CI depend on unavailable root-level manifests.

# Verification

- Validate YAML syntax/structure.
- Cross-check CI commands against `frontend/package.json` and `backend/composer.json`.
- If possible, run local equivalents from the correct subdirectory.

# Failure handling

- If CI configuration appears aspirational, document the mismatch and propose a minimal corrective path.
- If a third-party action's behavior is unknown, mark it `UNKNOWN` unless official docs are inspected.
