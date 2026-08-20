<template>
  <header class="topbar">
    <div>
      <p class="eyebrow">Thứ Hai, 17 tháng 8</p>
      <h1 class="m-0 text-3xl leading-tight">Xem mỗi sáng</h1>
      <p class="mt-2 text-muted">Một vài bước nhỏ hôm nay sẽ tạo khác biệt lớn.</p>
    </div>
    <button class="primary" type="button">Bắt đầu ngày</button>
  </header>

  <GoalCarousel />

  <div class="grid grid-cols-[minmax(0,1.18fr)_minmax(300px,0.82fr)] gap-4 mt-4">
    <div>
      <QuickNote />
      <RecentNotes />
    </div>
    <SchedulePanel />
  </div>
</template>

<script setup>
import GoalCarousel from './GoalCarousel.vue';
import QuickNote from './QuickNote.vue';
import RecentNotes from './RecentNotes.vue';
import SchedulePanel from './SchedulePanel.vue';
</script>

<style scoped>
.topbar { @apply flex justify-between items-start gap-5 mb-6; }
.eyebrow { @apply m-0 mb-1 text-blue-500 font-bold text-xs uppercase tracking-widest; }
.primary {
  @apply px-4 py-2.5 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-700;
  box-shadow: 0 5px 12px rgba(53, 136, 212, 0.18);
}

@media (max-width: 980px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
