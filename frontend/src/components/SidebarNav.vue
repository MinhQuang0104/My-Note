<template>
  <aside
    class="sidebar relative flex flex-col p-[18px_14px] bg-white border-r border-line overflow-hidden"
  >
    <router-link to="/" class="brand flex items-center gap-[11px] px-[9px] pb-6 whitespace-nowrap">
      <span
        class="brand-mark grid place-items-center w-9 h-9 rounded-[10px] bg-blue-500 text-white font-bold flex-none"
        >N</span
      >
      <span class="brand-copy">
        <strong class="text-lg">My Note</strong>
        <small class="block text-muted text-[11px]">Learn with intention</small>
      </span>
    </router-link>

    <button
      @click="$emit('toggleCompact')"
      class="collapse absolute top-6 right-3 w-7 h-7 border border-line rounded-[7px] bg-white text-blue-700 text-lg leading-none"
      type="button"
      aria-label="Ẩn thanh điều hướng"
      title="Ẩn thanh điều hướng"
    >
      <IconChevronLeft
        class="transition-transform duration-200"
        :class="{ 'rotate-180': isCompact }"
      />
    </button>

    <p
      class="nav-label px-[9px] pb-2 text-muted text-[11px] font-bold tracking-[.09em] uppercase"
    >
      Workspace
    </p>
    <nav class="nav grid gap-1" aria-label="Điều hướng chính">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center gap-3 px-[9px] py-2.5 rounded-lg text-muted whitespace-nowrap hover:bg-blue-100 hover:text-blue-700 hover:font-semibold"
        active-class="active"
      >
        <span
          class="nav-icon grid place-items-center w-[22px] h-[22px] border border-current rounded-md text-xs flex-none"
          >{{ item.icon }}</span
        >
        <span>{{ item.name }}</span>
      </router-link>
    </nav>

    <div
      class="profile mt-auto pt-[13px] border-t border-line flex items-center gap-[9px] whitespace-nowrap"
    >
      <span
        class="avatar grid place-items-center w-[34px] h-[34px] rounded-full bg-green-100 text-green font-bold flex-none"
        >A</span
      >
      <span class="profile-copy">
        <span class="block text-sm">Alex Nguyen</span>
        <small class="text-muted text-[11px]">Đang học tập</small>
      </span>
      <span class="profile-actions ml-auto grid gap-0.5 text-right text-[11px]">
        <router-link to="/login" class="text-blue-500">Tài khoản</router-link>
        <router-link to="/login" class="text-red">Đăng xuất</router-link>
      </span>
    </div>
  </aside>
</template>

<script setup>
import IconChevronLeft from './IconChevronLeft.vue';

defineProps({
  isCompact: Boolean,
});
defineEmits(['toggleCompact']);

const menuItems = [
  { name: 'Xem mỗi sáng', path: '/', icon: 'O' },
  { name: 'Ghi chú', path: '/notes', icon: 'N' },
  { name: 'Mục tiêu', path: '/goals', icon: 'G' },
  { name: 'Lịch', path: '/calendar', icon: 'L' },
];
</script>

<style scoped>
.nav a.active {
  @apply bg-blue-100 text-blue-700 font-semibold;
}

.app.compact .brand-copy,
.app.compact .nav-label,
.app.compact .nav a span:not(.nav-icon),
.app.compact .profile-copy,
.app.compact .profile-actions {
  display: none;
}

.app.compact .collapse {
  right: 24px;
}

.app.compact .nav a {
  justify-content: center;
}

@media (max-width: 820px) {
  .sidebar {
    padding: 14px 16px;
    border-right: 0;
    border-bottom: 1px solid theme('colors.line');
  }
  .brand {
    padding-bottom: 12px;
  }
  .collapse {
    top: 14px;
    right: 16px;
  }
  .nav-label,
  .profile {
    display: none;
  }
  .nav {
    display: flex;
    overflow: auto;
    gap: 6px;
  }
  .nav a {
    flex: 0 0 auto;
    padding: 8px 10px;
  }
  .app.compact .nav a span:not(.nav-icon) {
    display: block;
  }
  .app.compact .nav a {
    justify-content: flex-start;
  }
}
</style>
