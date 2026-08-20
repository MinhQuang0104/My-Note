<template>
  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="m-0 text-[17px]">Mục tiêu đang theo dõi</h2>
        <span class="text-muted text-xs">{{ goals.length }} mục tiêu</span>
      </div>
      <router-link to="/goals" class="link">Quản lý mục tiêu</router-link>
    </div>

    <div class="goal-carousel">
      <button
        @click="prevPage"
        class="carousel-arrow"
        :disabled="totalPages <= 1"
        type="button"
        aria-label="Mục tiêu trước"
      >
        ‹
      </button>
      <div class="goal-track">
        <button
          v-for="goal in visibleGoals"
          :key="goal.title"
          class="goal-card"
          type="button"
        >
          <h3 class="m-0 text-[15px] leading-tight">{{ goal.title }}</h3>
          <p class="goal-note">{{ goal.note }}</p>
          <div class="goal-stats">
            <div>
              <strong class="goal-stat-value">{{ goal.days }}</strong>
              <span class="goal-stat-label">ngày đã làm</span>
            </div>
            <div>
              <strong class="goal-stat-value">{{ goal.streak }}</strong>
              <span class="goal-stat-label">ngày streak</span>
            </div>
          </div>
        </button>
      </div>
      <button
        @click="nextPage"
        class="carousel-arrow"
        :disabled="totalPages <= 1"
        type="button"
        aria-label="Mục tiêu tiếp theo"
      >
        ›
      </button>
    </div>

    <div class="carousel-meta">
      <span
        v-for="(_, index) in Array.from({ length: totalPages })"
        :key="index"
        class="carousel-dot"
        :class="{ active: index === page }"
      ></span>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useGoals } from '../composables/useGoals';

const { goals } = useGoals();

const page = ref(0);
const itemsPerPage = 3;

const totalPages = computed(() => Math.ceil(goals.value.length / itemsPerPage));
const visibleGoals = computed(() => {
  const start = page.value * itemsPerPage;
  return goals.value.slice(start, start + itemsPerPage);
});

const prevPage = () => {
  page.value = (page.value + totalPages.value - 1) % totalPages.value;
};
const nextPage = () => {
  page.value = (page.value + 1) % totalPages.value;
};
</script>

<style scoped>
/* Scoped styles from the design file, converted to @apply if possible or kept as is */
.section {
  @apply bg-white border border-line rounded-[10px] p-5;
  box-shadow: var(--shadow);
}
.section-head {
  @apply flex justify-between items-center gap-[15px] mb-4;
}
.link {
  @apply text-blue-500 text-sm font-semibold;
}
.goal-carousel {
  @apply relative grid grid-cols-[38px_minmax(0,1fr)_38px] gap-2.5 items-stretch mb-5;
}
.carousel-arrow {
  @apply border border-line rounded-lg bg-white text-blue-700 text-2xl hover:bg-blue-100;
}
.carousel-arrow:disabled {
  @apply opacity-50 bg-gray-100 cursor-not-allowed;
}
.goal-track {
  @apply grid grid-cols-3 gap-3 min-w-0;
}
.goal-card {
  @apply min-w-0 p-4 border border-line rounded-[9px] bg-[#fbfdff] text-left transition-all duration-200;
}
.goal-card:hover,
.goal-card:focus {
  @apply border-blue-500 -translate-y-0.5;
  box-shadow: 0 8px 18px rgba(53, 136, 212, 0.12);
  outline: 0;
}
.goal-note {
  @apply min-h-[42px] my-[7px] mb-[15px] text-muted text-xs;
}
.goal-stats {
  @apply flex gap-3.5 border-t border-line pt-3;
}
.goal-stat-value {
  @apply block text-blue-700 text-xl leading-tight;
}
.goal-stat-label {
  @apply block text-muted text-[11px];
}
.carousel-meta {
  @apply flex justify-center gap-1.5 mt-2.5;
}
.carousel-dot {
  @apply w-[7px] h-[7px] rounded-full bg-blue-200;
}
.carousel-dot.active {
  @apply w-[19px] rounded-full bg-blue-500;
}

@media (max-width: 980px) {
  .goal-track {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 820px) {
  .goal-track {
    grid-template-columns: 1fr;
  }
}
</style>
