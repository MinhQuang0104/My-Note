import { ref } from 'vue'
import * as api from '../services/api'

const token = ref(localStorage.getItem('token') || null)
const user = ref(null)

function setToken(t) {
  token.value = t
  if (t) localStorage.setItem('token', t)
  else localStorage.removeItem('token')
}

async function register(payload) {
  const res = await api.register(payload)
  setToken(res.token)
  user.value = res.user
  return res
}

async function login(payload) {
  const res = await api.login(payload)
  setToken(res.token)
  user.value = res.user
  return res
}

function logout() {
  setToken(null)
  user.value = null
}

export function useAuth() {
  return { token, user, register, login, logout }
}
