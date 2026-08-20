<template>
  <section>
    <div class="section-head mt-7">
      <h2 class="m-0 text-[17px]">Ghi chú gần đây</h2>
      <router-link to="/notes" class="link">Xem tất cả</router-link>
    </div>
    <div class="list">
      <article v-for="note in notes" :key="note.title" class="note-row">
        <div class="row-top">
          <strong class="text-sm">{{ note.title }}</strong>
          <small class="text-muted whitespace-nowrap">{{ note.time }}</small>
        </div>
        <p class="row-text">{{ note.text }}</p>
      </article>
    </div>
  </section>
</template>

<script setup>
import { useGoals } from '../composables/useGoals';

const { notes } = useGoals();
</script>

<style scoped>
.section-head { @apply flex justify-between items-center gap-[15px] mb-4; }
.link { @apply text-blue-500 text-sm font-semibold; }
.list { @apply grid gap-3; }
.note-row { @apply p-3.5 border border-line rounded-lg; }
.row-top { @apply flex justify-between gap-4; }
.row-text { @apply mt-1.5 text-muted text-sm; }
</style>