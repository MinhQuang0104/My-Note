---
name: vue-vite-frontend-development
description: Use when implementing or changing Vue 3 views, composables, API service calls, frontend build behavior, or UI flows in the frontend application.
---

# Purpose

Guide Vue/Vite frontend work for the My Note SPA.

# When to use

- A task touches `frontend/src`, `frontend/package.json`, `frontend/vite.config.js`, or UI behavior.
- A task changes Notes, Goals, Calendar, or Auth screens.
- A task changes frontend API calls or `VITE_API_URL` behavior.

# When NOT to use

- Backend-only API implementation with no UI change.
- CI-only changes unless frontend scripts are involved.
- Static design reference updates in `docs/design_UI/` with no app code changes.

# Project-specific knowledge

- Frontend uses Vue 3 with `<script setup>` and Composition API refs.
- API functions live in `frontend/src/services/api.js`.
- Auth token state lives in `frontend/src/composables/useAuth.js` and persists to `localStorage`.
- `App.vue` switches views using local state, not Vue Router.
- Existing views: `NotesView.vue`, `GoalsView.vue`, `CalendarView.vue`, `AuthView.vue`.
- Default API base is `http://127.0.0.1:8000/api` unless `VITE_API_URL` is set.
- Existing UI labels are partly Vietnamese and may have mojibake encoding.

# Workflow

1. Inspect the relevant view, `api.js`, `useAuth.js`, and `style.css`.
2. Confirm whether a backend endpoint already exists.
3. Add or update API service functions before wiring view logic.
4. Keep state simple with `ref` unless the app introduces a shared store.
5. Preserve existing view-switching style unless a routing task explicitly introduces Vue Router.
6. Handle loading and error states consistently with current views.
7. Keep UI text consistent with the current language/encoding unless text repair is requested.

# Rules

- Do not add Pinia, Vue Router, TypeScript, or a test framework without explicit need.
- Do not hardcode production API URLs.
- Do not send `user_id` from the frontend for user-owned resources.
- Do not store extra sensitive data in `localStorage`.

# Verification

- Run `cd frontend && npm run build` for frontend code changes.
- If scripts are added, ensure `frontend/package.json` and CI expectations stay aligned.

# Failure handling

- If the frontend needs an endpoint that does not exist, identify the backend gap before faking data.
- If CI asks for scripts that do not exist, mark that as a repository issue instead of claiming CI coverage.
