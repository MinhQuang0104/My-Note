<script setup>
import { ref } from 'vue'
import AuthView from './views/AuthView.vue'
import NotesView from './views/NotesView.vue'
import GoalsView from './views/GoalsView.vue'
import CalendarView from './views/CalendarView.vue'

const currentView = ref('notes')
const navItems = [
  { key: 'notes', label: 'Ghi chú' },
  { key: 'goals', label: 'Mục tiêu' },
  { key: 'calendar', label: 'Lịch' },
]
</script>

<template>
  <div class="app-shell">
    <aside class="sidebar">
      <div>
        <h1>My Note</h1>
        <p>Monorepo MVP với Laravel + Vue.</p>
      </div>

      <nav>
        <button
          v-for="item in navItems"
          :key="item.key"
          :class="{ active: currentView === item.key }"
          @click="currentView = item.key"
        >
          {{ item.label }}
        </button>
      </nav>

      <div class="sidebar-footer">
        <AuthView mode="login" />
      </div>
    </aside>

    <main class="content">
      <NotesView v-if="currentView === 'notes'" />
      <GoalsView v-else-if="currentView === 'goals'" />
      <CalendarView v-else />
    </main>
  </div>
</template>
