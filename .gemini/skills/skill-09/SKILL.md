---
name: domain-api-contract-alignment
description: Use when reconciling implemented Notes/Goals/Calendar behavior with AI-Analyze product, domain, schema, or API contract documents.
---

# Purpose

Help Gemini distinguish implemented product behavior from planned or aspirational documents.

# When to use

- A task mentions product requirements, domain model, API contract, MVP scope, roadmap, or `AI-Analyze/`.
- A task asks whether current code matches the planned API/schema.
- A task changes Notes, Goals, Goal Entries, Calendar, or response formats based on documentation.

# When NOT to use

- Small code fixes where requirements are fully evident from implemented code.
- CI or infrastructure-only changes.

# Project-specific knowledge

- `AI-Analyze/` contains planning docs in Vietnamese.
- Implemented API is simpler than `AI-Analyze/09-api-contract.md`.
- Implemented goal model uses `title`, `description`, `target_date`, `is_completed`; planning docs mention richer fields such as `type`, `target_value`, `unit`, `repeat_rule`, `color`, `icon`.
- Implemented goal entries use `label`, `note`, `entry_date`; planning docs discuss `log_date`, `value`, and `status`.
- Implemented calendar route is `GET /api/calendar`; planning docs mention day/week endpoints.
- Implemented responses are raw JSON, not the planned standard success/error envelope.

# Workflow

1. Identify whether the task targets current implementation or future planned behavior.
2. Inspect the relevant `AI-Analyze` document and implemented code.
3. Mark facts as `CONFIRMED`, `INFERRED`, or `UNKNOWN`.
4. For alignment work, propose an incremental migration path before broad rewrites.
5. Update docs and code together only when requested.

# Rules

- Do not claim planned fields or endpoints exist unless code confirms them.
- Do not migrate response formats globally without explicit scope and tests.
- Preserve current MVP behavior unless the task asks for contract alignment.

# Verification

- For code alignment, run backend tests and frontend build if affected.
- For documentation alignment, verify no unsupported claims remain.

# Failure handling

- If documents conflict, prefer implemented code for current behavior and planning docs for intended future behavior.
- If the user asks for contract compliance, list breaking changes before implementation.
