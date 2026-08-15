<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../composables/useAuth'
import * as api from '../services/api'

const notes = ref([])
const loading = ref(false)
const err = ref(null)

const form = ref({ title: '', content: '' })

const editingId = ref(null)
const editBuffer = ref({ title: '', content: '' })

const { token } = useAuth()

async function load() {
  loading.value = true
  err.value = null
  try {
    notes.value = await api.getNotes(token.value)
  } catch (e) {
    err.value = e
  } finally {
    loading.value = false
  }
}

async function createNote() {
  err.value = null
  try {
    const n = await api.createNote(token.value, form.value)
    notes.value.unshift(n)
    form.value.title = ''
    form.value.content = ''
  } catch (e) {
    err.value = e
  }
}

function startEdit(n) {
  editingId.value = n.id
  editBuffer.value = { title: n.title, content: n.content }
}

function cancelEdit() {
  editingId.value = null
  editBuffer.value = { title: '', content: '' }
}

async function saveEdit(n) {
  err.value = null
  try {
    const updated = await api.updateNote(token.value, n.id, editBuffer.value)
    const idx = notes.value.findIndex(x => x.id === n.id)
    if (idx !== -1) notes.value[idx] = updated
    cancelEdit()
  } catch (e) { err.value = e }
}

async function removeNote(n) {
  if (!confirm('Delete this note?')) return
  err.value = null
  try {
    await api.deleteNote(token.value, n.id)
    notes.value = notes.value.filter(x => x.id !== n.id)
  } catch (e) { err.value = e }
}

onMounted(() => load())
</script>

<template>
  <section class="panel">
    <h2>Ghi chú</h2>

    <div v-if="err" class="error">Error: {{ typeof err === 'string' ? err : JSON.stringify(err) }}</div>

    <form @submit.prevent="createNote" style="margin-bottom:12px">
      <input v-model="form.title" placeholder="Tiêu đề" required />
      <br />
      <textarea v-model="form.content" placeholder="Nội dung"></textarea>
      <br />
      <button type="submit">Tạo ghi chú</button>
    </form>

    <div v-if="loading">Loading...</div>

    <ul>
      <li v-for="n in notes" :key="n.id" class="note-item">
        <div v-if="editingId !== n.id">
          <strong>{{ n.title }}</strong>
          <p>{{ n.content }}</p>
          <div class="actions">
            <button @click="startEdit(n)">Edit</button>
            <button @click="removeNote(n)" class="danger">Delete</button>
          </div>
        </div>

        <div v-else class="edit-box">
          <input v-model="editBuffer.title" />
          <textarea v-model="editBuffer.content"></textarea>
          <div class="actions">
            <button @click="saveEdit(n)">Save</button>
            <button @click="cancelEdit">Cancel</button>
          </div>
        </div>
      </li>
    </ul>
  </section>
</template>
