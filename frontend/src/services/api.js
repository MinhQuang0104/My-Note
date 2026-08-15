const BASE = import.meta.env.VITE_API_URL ?? 'http://127.0.0.1:8000/api'

async function parseBody(res) {
  const text = await res.text()
  try { return JSON.parse(text) } catch { return text }
}

async function handleResponse(res) {
  const body = await parseBody(res)
  if (!res.ok) throw body
  return body
}

function authHeaders(token) {
  const h = { 'Content-Type': 'application/json' }
  if (token) h['Authorization'] = `Bearer ${token}`
  return h
}

export async function register(payload) {
  const res = await fetch(`${BASE}/auth/register`, {
    method: 'POST',
    headers: authHeaders(),
    body: JSON.stringify(payload),
  })
  return handleResponse(res)
}

export async function login(payload) {
  const res = await fetch(`${BASE}/auth/login`, {
    method: 'POST',
    headers: authHeaders(),
    body: JSON.stringify(payload),
  })
  return handleResponse(res)
}

export async function getNotes(token) {
  const res = await fetch(`${BASE}/notes`, {
    headers: authHeaders(token),
  })
  return handleResponse(res)
}

export async function createNote(token, payload) {
  const res = await fetch(`${BASE}/notes`, {
    method: 'POST',
    headers: authHeaders(token),
    body: JSON.stringify(payload),
  })
  return handleResponse(res)
}

export async function updateNote(token, id, payload) {
  const res = await fetch(`${BASE}/notes/${id}`, {
    method: 'PUT',
    headers: authHeaders(token),
    body: JSON.stringify(payload),
  })
  return handleResponse(res)
}

export async function deleteNote(token, id) {
  const res = await fetch(`${BASE}/notes/${id}`, {
    method: 'DELETE',
    headers: authHeaders(token),
  })
  return handleResponse(res)
}

export async function getGoals(token) {
  const res = await fetch(`${BASE}/goals`, {
    headers: authHeaders(token),
  })
  return handleResponse(res)
}

export async function createGoal(token, payload) {
  const res = await fetch(`${BASE}/goals`, {
    method: 'POST',
    headers: authHeaders(token),
    body: JSON.stringify(payload),
  })
  return handleResponse(res)
}

export async function updateGoal(token, id, payload) {
  const res = await fetch(`${BASE}/goals/${id}`, {
    method: 'PUT',
    headers: authHeaders(token),
    body: JSON.stringify(payload),
  })
  return handleResponse(res)
}

export async function deleteGoal(token, id) {
  const res = await fetch(`${BASE}/goals/${id}`, {
    method: 'DELETE',
    headers: authHeaders(token),
  })
  return handleResponse(res)
}

export default { register, login, getNotes, createNote, getGoals, createGoal }
