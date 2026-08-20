<template>
  <div
    class="app grid min-h-screen"
    :class="{ compact: isCompact }"
    style="transition: grid-template-columns 0.25s ease"
  >
    <SidebarNav :is-compact="isCompact" @toggle-compact="toggleCompact" />
    <main class="main min-w-0 p-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import SidebarNav from '../components/SidebarNav.vue';

const isCompact = ref(false);

const toggleCompact = () => {
  isCompact.value = !isCompact.value;
};
</script>

<style scoped>
.app {
  grid-template-columns: 248px minmax(0, 1fr);
}
.app.compact {
  grid-template-columns: 76px minmax(0, 1fr);
}

/* Responsive styles from design */
@media (max-width: 820px) {
  .app,
  .app.compact {
    display: block;
    grid-template-columns: 1fr;
  }
  .main {
    padding: 25px 18px;
  }
}
</style>
