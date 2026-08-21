# 15. Gap And Next Actions

Lan quet: 2026-08-17 15:18:03 +07:00

## Chenh lech giua tai lieu va implementation

### API response format

Tai lieu `09-api-contract.md` de xuat response dang:

```json
{ "success": true, "data": {}, "meta": {} }
```

Code hien tai tra ve JSON truc tiep, vi du:

```json
[{ "id": 1, "title": "..." }]
```

Can quyet dinh giu JSON truc tiep cho MVP hay chuan hoa response wrapper.

### Notes

Tai lieu co `tags`, search, archived filter, pagination. Code hien tai moi co:

- `title`
- `content`
- `is_archived`
- CRUD co ban

Chua co `tags`, search/filter, pagination.

### Goals

Tai lieu du kien model linh hoat:

- `name`
- `type`
- `target_value`
- `unit`
- `repeat_rule`
- `start_date`
- `end_date`
- `is_active`
- `color`
- `icon`

Code hien tai dang don gian hon:

- `title`
- `description`
- `target_date`
- `is_completed`

Can chon huong: giu model don gian de hoc nhanh, hay migrate sang model goal tracking linh hoat.

### Goal entries

Tai lieu du kien entry co:

- `log_date`
- `value`
- `status`
- `note`

Code hien tai co:

- `label`
- `note`
- `entry_date`

Frontend chua co man hinh tao/xem/sua/xoa goal entries.

### Calendar

Tai lieu du kien:

- `GET /api/calendar/day`
- `GET /api/calendar/week`
- day/week summary co status.

Code hien tai:

- `GET /api/calendar`
- tra ve 10 notes, 10 goals, 10 goal entries moi nhat.
- frontend calendar chi la placeholder.

### CI

Workflow CI da duoc tao nhung co kha nang chua chay duoc ngay vi:

- Frontend scripts `lint`, `type-check`, `test:unit` chua ton tai.
- Backend co the thieu dependency/config cho `phpstan` va `insights`.
- Workflow chua dat `working-directory` cho frontend/backend trong nhieu step.

## Uu tien de tiep tuc

1. Sua encoding tieng Viet trong frontend va tai lieu quan trong.
2. Chay kiem tra local: `npm run build` trong `frontend/`, `composer test` hoac `php artisan test` trong `backend/`.
3. Sua CI de khop repo hien tai:
   - Them `working-directory`.
   - Chi chay scripts that su ton tai hoac them scripts/dependencies con thieu.
4. Hoan thien auth UX:
   - Them logout.
   - An notes/goals/calendar khi chua login hoac hien thong bao dang nhap.
   - Goi `/auth/me` khi reload neu token con trong localStorage.
5. Hoan thien goal entries:
   - Them API client functions.
   - Them UI trong `GoalsView.vue` hoac mot `GoalDetailView.vue`.
6. Hoan thien calendar:
   - Frontend goi `GET /api/calendar`.
   - Sau do tach thanh day/week endpoint neu can.
7. Quyet dinh data model Goals:
   - Huong MVP nhanh: giu `title/target_date/is_completed`.
   - Huong tracking nghiem tuc: them `type/target_value/unit/repeat_rule/start_date/status`.

## De xuat sprint tiep theo

Sprint tiep theo nen tap trung vao do on dinh thay vi mo rong lon:

- Fix chu tieng Viet bi loi encoding tren Vue UI.
- Sua CI cho chay duoc voi code hien tai.
- Them frontend calendar summary bang API dang co.
- Them UI goal entries toi thieu.
- Them feature tests cho auth, notes, goals authorization.
