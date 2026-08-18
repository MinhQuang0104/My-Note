---
name: database-migrations-and-eloquent
description: Use when modifying Laravel migrations, Eloquent models, relationships, fillable attributes, database configuration, or data schema documentation.
---

# Purpose

Keep schema and Eloquent model work consistent with the implemented backend.

# When to use

- A task touches `backend/database/migrations`, `backend/app/Models`, or `backend/config/database.php`.
- A task adds fields to notes, goals, goal entries, users, or calendar-related data.
- A task asks about Postgres, SQLite testing, relationships, indexes, or schema plans.

# When NOT to use

- Frontend-only form changes that do not require schema changes.
- Terraform-only database provisioning with no Laravel schema change.

# Project-specific knowledge

- Implemented domain tables: `users`, `notes`, `goals`, `goal_entries`, Sanctum personal access tokens, cache, and jobs.
- `notes`: `user_id`, `title`, nullable `content`, `is_archived`, timestamps.
- `goals`: `user_id`, `title`, nullable `description`, nullable `target_date`, `is_completed`, timestamps.
- `goal_entries`: `goal_id`, `user_id`, `label`, nullable `note`, `entry_date`, timestamps.
- Current PHPUnit config uses SQLite in-memory.
- Docker Compose provides Postgres 15 for local development.
- Planning docs mention richer goal fields and calendar events, but those are not implemented.

# Workflow

1. Inspect the current migration and model for the affected entity.
2. Compare requested changes against implemented schema and `AI-Analyze` plans.
3. Add new migrations rather than editing applied historical migrations unless the user explicitly says the project is pre-release and wants squashing.
4. Update model `$fillable`, casts, relationships, and controller validation together.
5. Consider indexes for user-scoped lists and date lookups when adding query-heavy fields.
6. Keep SQLite test compatibility in mind when using Postgres-specific features.

# Rules

- Do not silently implement planned fields as if they already exist.
- Do not add destructive migrations without explicit approval and rollback notes.
- Keep foreign keys cascading only when deleting parent data should delete owned child records.

# Verification

- Run `cd backend && php artisan test`.
- For migration-only work, also consider `cd backend && php artisan migrate --pretend` if the environment is configured.

# Failure handling

- If Postgres-specific schema is needed but tests use SQLite, document the compatibility risk and add database-specific validation guidance.
- If schema and API contract disagree, identify both and ask before large alignment work.
