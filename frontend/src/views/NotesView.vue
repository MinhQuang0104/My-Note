<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../composables/useAuth'
import * as api from '../services/api'

const notes = ref([])
const loading = ref(false)
const err = ref(null)

const form = ref({ title: '', content: '' })

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
      <li v-for="n in notes" :key="n.id">
        <strong>{{ n.title }}</strong>
        <p>{{ n.content }}</p>
      </li>
    </ul>
  </section>
</template>
