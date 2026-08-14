const BASE = import.meta.env.VITE_API_URL ?? 'http://127.0.0.1:8000/api'

async function handleResponse(res) {
  const text = await res.text()
  try { return JSON.parse(text) }
  catch { if (!res.ok) throw { message: res.statusText, body: text }; return text }
}

export async function register(payload) {
  const res = await fetch(`${BASE}/auth/register`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  })
  if (!res.ok) throw await handleResponse(res)
  return handleResponse(res)
}

export async function login(payload) {
  const res = await fetch(`${BASE}/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  })
  if (!res.ok) throw await handleResponse(res)
  return handleResponse(res)
}

export async function getNotes(token) {
  const res = await fetch(`${BASE}/notes`, {
    headers: { Authorization: `Bearer ${token}` },
  })
  if (!res.ok) throw await handleResponse(res)
  return handleResponse(res)
}

export default { register, login, getNotes }
