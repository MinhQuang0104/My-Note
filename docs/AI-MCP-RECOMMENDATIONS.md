# AI MCP Recommendations

This repository does not contain enough confirmed information to generate a ready-to-run MCP server configuration safely. The recommendations below use least privilege and placeholders where values are unknown.

Do not place credentials, API keys, database passwords, GitHub tokens, cloud keys, or production secrets in this repository.

## Recommended MCPs

### GitHub

- Purpose: Inspect pull requests, issues, workflow runs, code review comments, and repository metadata.
- Why this project needs it: CI is implemented with GitHub Actions and has likely directory/script mismatches that benefit from workflow-run inspection.
- Required capabilities: read repository contents, read workflow runs, read pull requests, read issues. Optional write access for comments only after team approval.
- Security considerations: use repository-scoped token; avoid broad organization admin scopes.
- Access: read-only recommended.
- Priority: High.
- Status: Recommended.
- Configuration placeholders: `TODO_GITHUB_OWNER`, `TODO_GITHUB_REPO`, `TODO_GITHUB_TOKEN`.

### Filesystem

- Purpose: Local repository-aware reads and writes for agent configuration, code, docs, and tests.
- Why this project needs it: Gemini must inspect monorepo structure and make scoped changes in `frontend/`, `backend/`, `infra/`, `.gemini/`, and `docs/`.
- Required capabilities: read/write within the repository only.
- Security considerations: deny access to home directories and global credential stores; do not expose `.env` values.
- Access: read/write limited to the repository.
- Priority: High.
- Status: Recommended.
- Configuration placeholders: `TODO_REPOSITORY_ABSOLUTE_PATH`.

### Docker

- Purpose: Inspect and validate local Compose services.
- Why this project needs it: `docker-compose.yml` defines local Postgres, backend, and frontend services.
- Required capabilities: read Compose config, list project containers, view logs. Optional start/stop for local project services.
- Security considerations: Docker socket access can be equivalent to host access; prefer read-only/log access when possible.
- Access: read-only by default; write/start/stop optional for local development only.
- Priority: Medium.
- Status: Recommended for local development.
- Configuration placeholders: `TODO_DOCKER_CONTEXT`, `TODO_COMPOSE_PROJECT`.

### PostgreSQL

- Purpose: Inspect local development schema and query local data safely.
- Why this project needs it: Docker Compose runs Postgres 15 and planning docs target Postgres for staging/prod.
- Required capabilities: read schema, list tables/indexes, run read-only queries. Optional migration validation in local dev only.
- Security considerations: never connect to production with write access; do not expose row data that may contain private notes.
- Access: read-only local database recommended.
- Priority: Medium.
- Status: Optional until database workflows mature.
- Configuration placeholders: `TODO_LOCAL_DB_HOST`, `TODO_LOCAL_DB_PORT`, `TODO_LOCAL_DB_NAME`, `TODO_READONLY_DB_USER`.

### Terraform

- Purpose: Format, validate, and inspect future Terraform modules and plans.
- Why this project needs it: `infra/terraform` is currently a skeleton and CI references Terraform validation.
- Required capabilities: read IaC files, run `terraform fmt` and `terraform validate`; optionally generate plans without applying.
- Security considerations: never apply changes automatically; protect backend state credentials.
- Access: read-only/plan-only.
- Priority: Low now, higher after `.tf` files exist.
- Status: Optional.
- Configuration placeholders: `TODO_TERRAFORM_WORKING_DIR`, `TODO_TERRAFORM_BACKEND`.

### AWS

- Purpose: Inspect future AWS deployment resources if AWS is selected.
- Why this project needs it: `AI-Analyze/12-ci-cd-aws-plan.md` discusses AWS, but no implemented AWS IaC exists.
- Required capabilities: read-only inventory for selected services after architecture is confirmed.
- Security considerations: strict read-only IAM role; no production mutation; no secret value reads.
- Access: read-only.
- Priority: Low until AWS deployment is confirmed.
- Status: Optional / future.
- Configuration placeholders: `TODO_AWS_ACCOUNT_ID`, `TODO_AWS_REGION`, `TODO_READONLY_ROLE_ARN`.

## Not Recommended Yet

- Kubernetes MCP: no Kubernetes manifests, Helm charts, or cluster config were observed.
- Monitoring/observability MCP: no Prometheus, Grafana, OpenTelemetry, Sentry, or dashboard config was observed.
- Ticketing MCP: no Jira, Linear, Asana, or issue workflow was observed in the repository.
- Production database write MCP: unnecessary and high risk for this MVP.

## Configuration Guidance

If an MCP configuration file is added later:

1. Use environment variables for tokens and passwords.
2. Prefer read-only scopes first.
3. Limit filesystem access to this repository.
4. Keep production and local development credentials separate.
5. Document every permission and why it is needed.
6. Never commit populated credentials.
