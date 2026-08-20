<template>
  <section class="section">
    <div class="section-head">
      <h2 class="m-0 text-[17px]">Viết nhanh</h2>
      <span class="link">Ghi chú cá nhân</span>
    </div>
    <form class="quick-note">
      <input class="field" placeholder="Tiêu đề ghi chú" />
      <textarea class="field min-h-[104px] resize-y" placeholder="Bạn đang nghĩ gì?"></textarea>
      <div class="form-foot">
        <span class="text-muted text-xs">Ghi chú sẽ được lưu vào workspace cá nhân.</span>
        <button class="primary" type="button">Lưu ghi chú</button>
      </div>
    </form>
  </section>
</template>

<style scoped>
.section { @apply bg-white border border-line rounded-[10px] p-5; box-shadow: var(--shadow); }
.section-head { @apply flex justify-between items-center gap-[15px] mb-4; }
.link { @apply text-blue-500 text-sm font-semibold; }
.quick-note { @apply grid gap-3; }
.field { @apply w-full px-3 py-3 border border-line rounded-lg bg-blue-25 text-ink outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100; }
.form-foot { @apply flex justify-between items-center; }
.primary {
  @apply px-4 py-2.5 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-700;
  box-shadow: 0 5px 12px rgba(53, 136, 212, 0.18);
}
</style>