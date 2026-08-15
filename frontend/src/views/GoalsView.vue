<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../composables/useAuth'
import * as api from '../services/api'

const goals = ref([])
const loading = ref(false)
const err = ref(null)

const form = ref({ title: '', description: '', target_date: '' })

const editingId = ref(null)
const editBuffer = ref({ title: '', description: '', target_date: '' })

const { token } = useAuth()

async function load() {
  loading.value = true
  err.value = null
  try {
    goals.value = await api.getGoals(token.value)
  } catch (e) {
    err.value = e
  } finally { loading.value = false }
}

async function createGoal() {
  err.value = null
  try {
    const g = await api.createGoal(token.value, form.value)
    goals.value.unshift(g)
    form.value.title = ''
    form.value.description = ''
    form.value.target_date = ''
  } catch (e) { err.value = e }
}

function startEdit(g) {
  editingId.value = g.id
  editBuffer.value = { title: g.title, description: g.description, target_date: g.target_date }
}

function cancelEdit() { editingId.value = null; editBuffer.value = { title: '', description: '', target_date: '' } }

async function saveEdit(g) {
  err.value = null
  try {
    const updated = await api.updateGoal(token.value, g.id, editBuffer.value)
    const idx = goals.value.findIndex(x => x.id === g.id)
    if (idx !== -1) goals.value[idx] = updated
    cancelEdit()
  } catch (e) { err.value = e }
}

async function removeGoal(g) {
  if (!confirm('Delete this goal?')) return
  err.value = null
  try {
    await api.deleteGoal(token.value, g.id)
    goals.value = goals.value.filter(x => x.id !== g.id)
  } catch (e) { err.value = e }
}

onMounted(() => load())
</script>

<template>
  <section class="panel">
    <h2>Mục tiêu</h2>

    <div v-if="err" class="error">Error: {{ typeof err === 'string' ? err : JSON.stringify(err) }}</div>

    <form @submit.prevent="createGoal" style="margin-bottom:12px">
      <input v-model="form.title" placeholder="Tiêu đề mục tiêu" required />
      <br />
      <input v-model="form.target_date" placeholder="YYYY-MM-DD" />
      <br />
      <textarea v-model="form.description" placeholder="Mô tả"></textarea>
      <br />
      <button type="submit">Tạo mục tiêu</button>
    </form>

    <div v-if="loading">Loading...</div>

    <ul>
      <li v-for="g in goals" :key="g.id" class="goal-item">
        <div v-if="editingId !== g.id">
          <strong>{{ g.title }}</strong> — <em>{{ g.target_date || 'No date' }}</em>
          <p>{{ g.description }}</p>
          <div class="actions">
            <button @click="startEdit(g)">Edit</button>
            <button @click="removeGoal(g)" class="danger">Delete</button>
          </div>
        </div>

        <div v-else class="edit-box">
          <input v-model="editBuffer.title" />
          <input v-model="editBuffer.target_date" placeholder="YYYY-MM-DD" />
          <textarea v-model="editBuffer.description"></textarea>
          <div class="actions">
            <button @click="saveEdit(g)">Save</button>
            <button @click="cancelEdit">Cancel</button>
          </div>
        </div>
      </li>
    </ul>
  </section>
</template>
