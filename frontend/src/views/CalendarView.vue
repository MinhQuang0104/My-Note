<script setup>
const weekdays = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']
const leadingDays = [27, 28, 29, 30, 31]
const monthDays = Array.from({ length: 31 }, (_, index) => index + 1)
const events = {
  1: [{ title: 'Doc sach', time: '08:00', tone: 'green' }],
  4: [{ title: 'On Vue API', time: '18:00', tone: '' }],
  6: [{ title: 'Review tuan', time: '20:30', tone: 'gold' }],
  10: [{ title: 'Doc sach', time: '08:00', tone: 'green' }],
  12: [{ title: 'On Vue API', time: '18:00', tone: '' }],
  17: [{ title: 'On Vue API', time: '18:00', tone: '' }],
  18: [{ title: 'Viet reflection', time: '20:30', tone: 'green' }],
  21: [{ title: 'Review tuan', time: '20:30', tone: 'gold' }],
}
</script>

<template>
  <header class="topbar">
    <div>
      <p class="eyebrow">Workspace / Lich</p>
      <h1>Lich hoc tap</h1>
      <p>Nhin lai nhip hoc va chu dong sap xep thoi gian.</p>
    </div>
    <button class="primary" type="button">+ Them lich</button>
  </header>

  <section class="calendar-box">
    <div class="calendar-head">
      <strong class="ornament" data-icon="C">Thang 8, 2026</strong>
      <div class="calendar-actions">
        <button class="secondary" type="button">Hom nay</button>
        <button class="secondary icon" type="button">‹</button>
        <button class="secondary icon" type="button">›</button>
      </div>
    </div>
    <div class="month-grid">
      <div v-for="day in weekdays" :key="day" class="weekday">{{ day }}</div>
      <div v-for="day in leadingDays" :key="`lead-${day}`" class="day muted-day">
        <div class="date"><span>{{ day }}</span></div>
      </div>
      <div v-for="day in monthDays" :key="day" class="day">
        <div class="date">
          <span :class="{ today: day === 17 }">{{ day }}</span>
        </div>
        <div v-for="event in events[day]" :key="event.title" class="event" :class="event.tone">
          {{ event.title }}<br /><small>{{ event.time }}</small>
        </div>
      </div>
    </div>
  </section>

  <div class="agenda">
    <section class="agenda-panel">
      <h2 class="ornament yellow" data-icon="*">Lich hom nay</h2>
      <div class="agenda-item">
        <span class="time">18:00</span>
        <i class="check"></i>
        <span><strong>On tap Vue Composition API</strong><small>Tiep tuc muc tieu "Hoan thanh module Vue"</small></span>
      </div>
      <div class="agenda-item">
        <span class="time">20:30</span>
        <i class="check green"></i>
        <span><strong>Viet reflection tuan</strong><small>Tom tat dieu da hoc trong tuan 33</small></span>
      </div>
    </section>
    <aside class="agenda-panel">
      <h2 class="ornament yellow" data-icon="*">Nhip hoc</h2>
      <p class="hint">Ban da co hoat dong trong <strong>5 ngay lien tiep</strong>. Hay giu mot phien hoc ngan hom nay de khong mat nhip.</p>
    </aside>
  </div>
</template>

<style scoped>
.calendar-box { overflow: auto; }
.calendar-head { display: flex; justify-content: space-between; align-items: center; padding: 17px 20px; border-bottom: 1px solid theme('colors.line'); }
.calendar-head strong { font-size: 16px; }
.calendar-actions { display: flex; gap: 8px; }
.calendar-actions .icon { width: 38px; padding-inline: 0; }
.month-grid { min-width: 720px; display: grid; grid-template-columns: repeat(7, 1fr); }
.weekday { padding: 12px 10px; border-right: 1px solid theme('colors.line'); border-bottom: 1px solid theme('colors.line'); color: theme('colors.muted'); font-size: 11px; font-weight: 700; text-transform: uppercase; }
.weekday:nth-child(7) { border-right: 0; }
.day { min-height: 142px; padding: 11px 10px; border-right: 1px solid theme('colors.line'); border-bottom: 1px solid theme('colors.line'); }
.day:nth-child(7n) { border-right: 0; }
.muted-day { background: #fbfdff; color: #b7c5d0; }
.date { display: flex; justify-content: space-between; align-items: center; margin-bottom: 9px; color: theme('colors.muted'); font-size: 12px; }
.today { display: grid; place-items: center; width: 25px; height: 25px; border-radius: 50%; background: theme('colors.blue.500'); color: #fff; }
.event { display: block; margin: 6px 0; padding: 7px 8px; border-left: 3px solid theme('colors.blue.500'); border-radius: 4px; background: theme('colors.blue.100'); font-size: 11px; line-height: 1.35; }
.event.green { border-color: theme('colors.green'); background: #e8f7f0; }
.event.gold { border-color: theme('colors.yellow'); background: #fff5d9; }
.agenda { display: grid; grid-template-columns: 1.2fr .8fr; gap: 16px; margin-top: 18px; }
.agenda-panel { padding: 19px 20px; }
.agenda-panel h2 { margin: 0 0 14px; font-size: 16px; }
.agenda-item { display: flex; gap: 13px; padding: 12px 0; border-bottom: 1px solid theme('colors.line'); }
.agenda-item:last-child { border: 0; }
.time { width: 74px; color: theme('colors.muted'); font-size: 12px; }
.agenda-item strong { display: block; font-size: 13px; }
.agenda-item small { display: block; color: theme('colors.muted'); margin-top: 3px; }
.check { width: 10px; height: 10px; margin-top: 5px; border-radius: 50%; background: theme('colors.blue.500'); flex: none; }
.check.green { background: theme('colors.green'); }
.hint { padding: 16px; background: theme('colors.blue.100'); border-radius: 7px; color: theme('colors.blue.700'); font-size: 13px; }
.yellow::before { background: #fff5d9; color: #d9a33f; box-shadow: 0 2px 0 #efd69d, 0 5px 10px rgba(53, 136, 212, .14); transform: rotate(5deg); }
@media (max-width: 820px) { .agenda { grid-template-columns: 1fr; } }
</style>
