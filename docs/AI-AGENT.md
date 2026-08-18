# AI Agent Configuration

This document explains the Gemini agent architecture for Project My Note and how developers should use and maintain it.

## Agent Architecture

The agent is designed with a **Token-Efficient Architecture** using progressive disclosure:

1.  **`GEMINI.md` (Always Loaded):** Contains essential, global facts about the project. It's the minimal context loaded for every task.
2.  **Skills (On-Demand):** A set of 10 skills under `.gemini/skills/` are activated for specific tasks (e.g., database work, frontend UI changes). This avoids loading irrelevant instructions.
3.  **Deep References:** Detailed documentation (e.g., `AI-Analyze/` docs, architecture diagrams) are not loaded by default. Skills will instruct the agent to reference these files when deep knowledge is required.
4.  **MCPs (Tools):** External tools like `docker` or `filesystem` are used when a skill's workflow requires them.

## Skill Inventory

The project uses exactly 10 specialized skills. The agent will automatically select the appropriate skill based on the task description. The skills are located in `.gemini/skills/` and cover topics from backend/frontend development to CI/CD and database management.

## How Developers Should Use The Agent

- Put broad repository rules or durable facts in `GEMINI.md`.
- Put task-specific workflows in the relevant skill.
- Keep new project facts evidence-based. Use `CONFIRMED`, `INFERRED`, or `UNKNOWN`.
- For complex tasks, ask the agent to "inspect the code and use the relevant skill to create a plan."
- For security-sensitive tasks, explicitly require least privilege and no secret disclosure.
- For CI tasks, ask the agent to compare workflow commands against actual manifests.

## Maintaining Skills

Update skills when:

- New frameworks, packages, test tools, or deployment systems are actually added.
- API response conventions change globally.
- The frontend introduces Vue Router, Pinia, TypeScript, or test tooling.
- Terraform files or production deployment resources are implemented.
- Observability, monitoring, or incident docs are added.

Do not update skills based only on ideas in planning docs unless the skill clearly marks them as planned or inferred.

## Updating After Architecture Changes

When architecture changes:

1. Inspect the implemented code and config first.
2. Update `GEMINI.md` with only global facts.
3. Update the smallest relevant skill.
4. Move lengthy details into references only if the skill becomes too large.
5. Re-run validation to ensure exactly 10 skills remain unless the project intentionally changes that requirement.

## MCP Recommendations

The project recommends a minimal set of MCPs (Managed Component/Tools) with least-privilege access.
- **High Priority:** `filesystem` (read/write), `github` (read-only).
- **Medium Priority:** `docker` (read/execute), `postgres` (local, read-only).
- **Low Priority/Future:** `terraform`, `aws`.

## Safety Rules

- Do not expose `.env` values.
- Do not run destructive database, Docker volume, Git, Terraform, or production commands without explicit approval.
- Do not assume production infrastructure exists.
- Do not weaken Sanctum or ownership checks to make tests pass.
- Do not modify application source code for agent-documentation-only tasks.

## Validation Expectations

For future changes:

- Backend code: run `cd backend && php artisan test`.
- Backend formatting: run Laravel Pint when PHP files change.
- Frontend code: run `cd frontend && npm run build`.
- CI changes: validate YAML and compare commands with package/composer scripts.
- Agent changes: verify exactly 10 `SKILL.md` files and unique skill names.
