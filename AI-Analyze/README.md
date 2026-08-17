# Project My Note - AI Analysis Context

Thu muc nay luu ngu canh phan tich san pham, ky thuat va tien do hien tai cho cac session AI/BA/Dev tiep theo.

## Nen tang san pham

Project My Note la monorepo hoc tap va xay dung ung dung ghi chu ca nhan, gom:

- Frontend Vue 3 + Vite.
- Backend Laravel API + Sanctum.
- PostgreSQL local qua Docker Compose.
- Tai lieu phan tich, ke hoach CI/CD va ha tang trong `AI-Analyze/`, `.github/`, `infra/`.

## File nen doc truoc

- [analysis-history.md](analysis-history.md): lich su cac lan quet/phan tich.
- [14-current-state-analysis.md](14-current-state-analysis.md): snapshot codebase moi nhat theo lan quet gan nhat.
- [15-gap-and-next-actions.md](15-gap-and-next-actions.md): chenhlech giua tai lieu cu va implementation, cung viec uu tien tiep theo.

## Tai lieu phan tich goc

- [01-product-vision.md](01-product-vision.md): tam nhin, muc tieu, doi tuong nguoi dung, gia tri san pham.
- [02-functional-requirements.md](02-functional-requirements.md): yeu cau chuc nang theo module.
- [03-user-stories.md](03-user-stories.md): user stories va acceptance criteria ban dau.
- [04-domain-model.md](04-domain-model.md): khai niem nghiep vu, entity, quan he du lieu.
- [05-mvp-scope.md](05-mvp-scope.md): pham vi MVP va cac giai doan sau.
- [06-technical-learning-roadmap.md](06-technical-learning-roadmap.md): lo trinh hoc ky thuat.
- [07-open-questions.md](07-open-questions.md): cau hoi can lam ro.
- [08-architecture-decision.md](08-architecture-decision.md): quyet dinh kien truc ban dau.
- [09-api-contract.md](09-api-contract.md): API contract du kien.
- [10-api-implementation-plan.md](10-api-implementation-plan.md): ke hoach implement API.
- [11-database-schema.md](11-database-schema.md): schema database du kien.
- [12-ci-cd-aws-plan.md](12-ci-cd-aws-plan.md): ke hoach CI/CD va AWS.
- [13-project-plan.md](13-project-plan.md): project plan theo phase/sprint.

## Ghi chu ve tai lieu cu

Mot so file goc dang bi loi hien thi tieng Viet do encoding. Khi can chinh sua sau nay, nen uu tien viet moi bang UTF-8 hoac thay the tung file bang ban sach, tranh copy tiep noi dung mojibake.

## Nguyen tac cap nhat

- Moi lan quet nen them mot entry vao [analysis-history.md](analysis-history.md).
- Neu implementation thay doi API/schema/frontend flow, cap nhat snapshot trong [14-current-state-analysis.md](14-current-state-analysis.md).
- Neu tai lieu goc va code that lech nhau, ghi ro trong [15-gap-and-next-actions.md](15-gap-and-next-actions.md).
