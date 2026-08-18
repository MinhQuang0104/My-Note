---
name: terraform-and-infrastructure-planning
description: Use when working on infra/terraform, infrastructure plans, cloud deployment recommendations, AWS notes, or IaC safety.
---

# Purpose

Keep infrastructure guidance grounded in the current skeleton and planning docs.

# When to use

- A task touches `infra/terraform` or `AI-Analyze/12-ci-cd-aws-plan.md`.
- A task mentions Terraform, AWS, deployment environments, managed Postgres, networking, IAM, secrets, or IaC.

# When NOT to use

- Local Docker-only changes.
- Application code changes with no deployment or infrastructure impact.

# Project-specific knowledge

- `infra/terraform/README.md` says Terraform is a skeleton for future deployment.
- No actual `.tf` files were observed.
- `AI-Analyze/12-ci-cd-aws-plan.md` discusses AWS-oriented CI/CD planning, but implementation is not present.
- GitHub Actions infra job references Pipery Terraform CI for `./infra`.
- Production provider and architecture are UNKNOWN.

# Workflow

1. Inspect `infra/`, CI infra job, and relevant planning docs.
2. Separate implemented infrastructure from planned infrastructure.
3. Recommend least-privilege modules and environments only after provider choice is confirmed.
4. Use placeholders such as `TODO` for unknown account IDs, regions, state backends, ARNs, and credentials.
5. Avoid creating provider-specific resources unless the user confirms the provider and target environment.

# Rules

- Do not invent AWS resources, Terraform backend config, domains, IAM roles, or secrets.
- Do not run `terraform apply` or production mutations.
- Prefer read-only validation and planning.

# Verification

- If `.tf` files exist, run `terraform fmt -check` and `terraform validate` only when safe and initialized.
- For skeleton docs, verify paths and TODO markers.

# Failure handling

- If exact infrastructure is unknown, produce recommendations instead of fake configuration.
- If CI references Terraform tooling without Terraform files, document the mismatch.
