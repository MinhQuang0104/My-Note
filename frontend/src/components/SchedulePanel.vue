<template>
  <section class="section schedule-panel">
    <div class="section-head">
      <h2 class="m-0 text-[17px]">Lịch trình của tôi</h2>
      <router-link to="/calendar" class="link">Mở lịch đầy đủ</router-link>
    </div>
    <div class="schedule-toolbar">
      <div class="view-switch" role="tablist">
        <button
          v-for="view in views"
          :key="view.id"
          :class="{ active: activeView === view.id }"
          @click="activeView = view.id"
          type="button"
        >
          {{ view.name }}
        </button>
      </div>
      <span class="schedule-date">{{ scheduleDateText }}</span>
    </div>
    <div class="schedule-list">
      <!-- Day View -->
      <div v-if="activeView === 'day' && schedules.day.length > 0">
        <div
          v-for="item in schedules.day"
          :key="item[1]"
          class="event"
        >
          <span class="event-time">{{ item[0] }}</span>
          <i class="dot"></i>
          <span>
            <span class="event-title">{{ item[1] }}</span>
            <small>{{ item[2] }}</small>
          </span>
        </div>
      </div>
      <div v-else-if="activeView === 'day'" class="empty">
        Không có lịch trình cho hôm nay.
      </div>
      <!-- Month View -->
      <div v-if="activeView === 'month'" class="month-view">
         <div class="month-heading">
            <strong>Tháng 8, 2026</strong>
            <span>8 hoạt động đã lên lịch</span>
          </div>
          <div class="month-grid">
            <span v-for="day in weekdays" :key="day" class="month-weekday">{{ day }}</span>
            <div v-for="(day, index) in monthDays" :key="index" :class="day.class">{{ day.d }}</div>
          </div>
      </div>
       <!-- Year View -->
      <div v-if="activeView === 'year'" class="year-view">
        <div class="year-summary">
          <span><strong>8</strong> tháng có hoạt động</span>
          <span><strong>96</strong> phiên học</span>
        </div>
        <div class="year-grid">
          <div v-for="month in schedules.year" :key="month[0]" class="year-month">
            <strong>{{ month[0] }}</strong>
            <small>{{ month[1] }}% mục tiêu</small>
            <div class="year-progress"><i :style="{ width: month[1] + '%' }"></i></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useGoals } from '../composables/useGoals';

const { schedules } = useGoals();

const activeView = ref('day');

const views = [
  { id: 'day', name: 'Ngày' },
  { id: 'month', name: 'Tháng' },
  { id: 'year', name: 'Năm' },
];

const scheduleDateText = computed(() => {
  return {
    day: '17 tháng 8, 2026',
    month: 'Tháng 8, 2026',
    year: 'Năm 2026',
  }[activeView.value];
});

const weekdays = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
const monthDays = [
    ...Array.from({length: 5}, () => ({d: '', class: 'month-day empty'})),
    ...Array.from({length: 31}, (_, i) => {
        const day = i + 1;
        let classes = 'month-day ';
        if (day === 17) classes += 'today ';
        if ([1,4,6,10,12,17,18,21].includes(day)) classes += 'has-event';
        return {d: day, class: classes};
    })
];

</script>

<style scoped>
/* Import all the complex styles from the design file */
@import url('../assets/schedule-styles.css');

.section { @apply bg-white border border-line rounded-[10px] p-5; box-shadow: var(--shadow); }
.section-head { @apply flex justify-between items-center gap-[15px] mb-4; }
.link { @apply text-blue-500 text-sm font-semibold; }
.schedule-toolbar { @apply flex items-center justify-between gap-2.5 mb-4; }
.view-switch { @apply flex gap-1 p-1 border border-line rounded-lg bg-[#fbfdff]; }
.view-switch button { @apply px-2.5 py-1.5 rounded-md bg-transparent text-muted text-xs; }
.view-switch button.active { @apply bg-blue-100 text-blue-700 font-bold; }
.schedule-date { @apply text-muted text-xs; }
.empty { @apply p-5 text-center text-muted bg-blue-25 rounded-lg text-sm; }
.schedule-list { @apply grid gap-3; }
</style>
