<script setup>
import { computed, ref } from 'vue'

const goals = ref([
  { title: 'Hoan thanh module Vue', description: 'Nam vung Composition API va hoan thien phan frontend cho My-Note.', date: '28 thang 8, 2026', days: 12, streak: 5, maxStreak: 10, done: [1, 2, 4, 5, 6, 8, 10, 12, 13, 15, 16, 17], notes: { 17: 'Da hoan thanh phien on tap Composition API va ghi lai cac diem can nho.' } },
  { title: 'Doc 2 sach ky thuat', description: 'Doc co ghi chu va viet reflection sau moi chuong.', date: '15 thang 9, 2026', days: 9, streak: 3, maxStreak: 8, done: [2, 3, 4, 7, 8, 11, 12, 16, 17], notes: { 17: 'Doc xong chuong 4 va ghi lai ba y chinh.' } },
  { title: 'Duy tri nhip hoc 30 ngay', description: 'Danh toi thieu 45 phut moi ngay cho viec hoc chu dong.', date: '30 thang 8, 2026', days: 21, streak: 10, maxStreak: 14, done: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 15, 16, 17], notes: { 17: 'Hoan thanh mot phien hoc tap trung 60 phut.' } },
])

const selected = ref(0)
const selectedDay = ref(17)
const showModal = ref(false)
const showToast = ref(false)
const newGoal = ref({ title: '', description: '', date: '' })
const weekdays = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']
const currentGoal = computed(() => goals.value[selected.value])
const blanks = computed(() => Array.from({ length: 5 }))
const days = computed(() => Array.from({ length: 31 }, (_, index) => index + 1))

function flashToast() {
  showToast.value = true
  window.setTimeout(() => {
    showToast.value = false
  }, 2200)
}

function createGoal() {
  goals.value.push({
    title: newGoal.value.title,
    description: newGoal.value.description || 'Chua co mo ta.',
    date: newGoal.value.date || 'Chua dat ngay',
    days: 0,
    streak: 0,
    maxStreak: 0,
    done: [],
    notes: {},
  })
  selected.value = goals.value.length - 1
  newGoal.value = { title: '', description: '', date: '' }
  showModal.value = false
  flashToast()
}
</script>

<template>
  <header class="topbar">
    <div>
      <p class="eyebrow">Workspace / Muc tieu</p>
      <h1>Muc tieu</h1>
      <p>Theo doi thoi quen bang lich hoan thanh va ghi chu thuc te.</p>
    </div>
    <button class="primary" type="button" @click="showModal = true">+ Tao muc tieu</button>
  </header>

  <div class="goals-content">
    <section class="panel goals-list-panel">
      <div class="head">
        <h2 class="ornament pink" data-icon="o">Dang theo doi</h2>
        <span class="muted">{{ goals.length.toString().padStart(2, '0') }} muc tieu</span>
      </div>
      <div class="goal-list">
        <button v-for="(goal, index) in goals" :key="goal.title" class="goal" :class="{ active: selected === index }" type="button" @click="selected = index">
          <div class="goal-top">
            <span class="goal-title">{{ goal.title }}</span>
            <span class="goal-date">{{ goal.date }}</span>
          </div>
          <p class="desc">{{ goal.description }}</p>
          <div class="meta">
            <span><strong>{{ goal.days }}</strong>da lam</span>
            <span><strong>{{ goal.streak }}</strong>streak hien tai</span>
            <span><strong>{{ goal.maxStreak }}</strong>streak cao nhat</span>
          </div>
        </button>
      </div>
    </section>

    <section class="panel detail-panel">
      <div class="detail-head">
        <div>
          <h2 class="ornament" data-icon="C">{{ currentGoal.title }}</h2>
          <p>{{ currentGoal.description }}</p>
        </div>
        <div class="month-nav">
          <button type="button">‹</button>
          <span class="month-label">Thang 8, 2026</span>
          <button type="button">›</button>
        </div>
      </div>

      <div class="goal-calendar">
        <span v-for="day in weekdays" :key="day" class="weekday">{{ day }}</span>
        <span v-for="(_, index) in blanks" :key="`blank-${index}`" class="date-cell empty"></span>
        <button
          v-for="day in days"
          :key="day"
          class="date-cell"
          :class="{ done: currentGoal.done.includes(day), today: day === 17 }"
          type="button"
          @click="selectedDay = day"
        >
          <span class="date-number">{{ day }}</span>
          <span class="date-status">{{ currentGoal.done.includes(day) ? 'Da lam' : 'Chua lam' }}</span>
        </button>
      </div>

      <div class="day-note">
        <h3>{{ selectedDay }} thang 8, 2026</h3>
        <p :class="{ 'empty-note': !currentGoal.notes[selectedDay] }">
          {{ currentGoal.notes[selectedDay] || 'Chua co ghi chu hoan thanh cho ngay nay.' }}
        </p>
      </div>
    </section>
  </div>

  <div v-if="showModal" class="modal-backdrop" @click.self="showModal = false">
    <section class="modal panel">
      <div class="modal-head">
        <div>
          <h2>Tao muc tieu moi</h2>
          <p>Dat mot muc tieu ro rang de theo doi moi ngay.</p>
        </div>
        <button class="modal-close" type="button" @click="showModal = false">x</button>
      </div>
      <form class="modal-form" @submit.prevent="createGoal">
        <label class="modal-label">Ten muc tieu<input v-model="newGoal.title" class="field" required /></label>
        <label class="modal-label">Mo ta<textarea v-model="newGoal.description" class="field"></textarea></label>
        <label class="modal-label">Ngay hoan thanh du kien<input v-model="newGoal.date" class="field" type="date" /></label>
        <div class="modal-actions">
          <button class="secondary" type="button" @click="showModal = false">Huy</button>
          <button class="primary" type="submit">Luu muc tieu</button>
        </div>
      </form>
    </section>
  </div>

  <div class="toast" :class="{ show: showToast }">Da tao muc tieu moi.</div>
</template>

<style scoped>
.goals-content { display: grid; grid-template-columns: minmax(330px, .85fr) minmax(420px, 1.15fr); gap: 18px; align-items: start; }
.goals-list-panel, .detail-panel { padding: 21px; }
.head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 17px; }
.head h2 { margin: 0; font-size: 17px; }
.goal-list { display: grid; gap: 9px; }
.goal { padding: 15px; border: 1px solid theme('colors.line'); border-radius: 8px; background: #fff; color: theme('colors.ink'); text-align: left; transition: .2s; }
.goal:hover, .goal.active { border-color: theme('colors.blue.500'); background: theme('colors.blue.100'); box-shadow: 0 7px 16px rgba(53, 136, 212, .1); }
.goal-top { display: flex; justify-content: space-between; gap: 15px; }
.goal-title { font-weight: 700; }
.goal-date { color: theme('colors.muted'); font-size: 12px; white-space: nowrap; }
.desc { margin: 5px 0 13px; color: theme('colors.muted'); font-size: 13px; }
.meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding-top: 11px; border-top: 1px solid theme('colors.line'); }
.meta strong { display: block; color: theme('colors.blue.700'); font-size: 18px; line-height: 1.1; }
.meta span { color: theme('colors.muted'); font-size: 11px; }
.detail-head { display: flex; justify-content: space-between; gap: 15px; align-items: flex-start; margin-bottom: 18px; }
.detail-head h2 { margin: 0; font-size: 22px; line-height: 1.2; }
.detail-head p { margin: 6px 0 0; color: theme('colors.muted'); font-size: 13px; }
.month-nav { display: flex; align-items: center; gap: 7px; }
.month-nav button { width: 30px; height: 30px; border: 1px solid theme('colors.line'); border-radius: 6px; background: #fff; color: theme('colors.blue.700'); font-size: 17px; }
.month-label { min-width: 105px; text-align: center; color: theme('colors.muted'); font-size: 12px; }
.goal-calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
.weekday { text-align: center; color: theme('colors.muted'); font-size: 10px; font-weight: 700; padding-bottom: 4px; }
.date-cell { min-height: 66px; padding: 8px; border: 1px solid theme('colors.line'); border-radius: 7px; background: #f3f5f7; color: #7d8c98; text-align: left; transition: .2s; }
.date-cell.done { background: #e7f7ef; border-color: #bfe7d4; color: theme('colors.green'); }
.date-cell.today { box-shadow: 0 0 0 2px theme('colors.blue.500'); }
.date-cell.empty { border-color: transparent; background: transparent; }
.date-number { font-size: 12px; font-weight: 700; }
.date-status { display: block; margin-top: 11px; font-size: 10px; }
.day-note { margin-top: 17px; padding: 15px; border-radius: 8px; background: theme('colors.blue.100'); min-height: 84px; }
.day-note h3 { margin: 0 0 5px; font-size: 14px; }
.day-note p { margin: 0; color: theme('colors.muted'); font-size: 13px; }
.empty-note { color: #9aabb7 !important; font-style: italic; }
.pink::before { background: #fff0f3; color: #e98b9f; box-shadow: 0 2px 0 #f3c3ce, 0 5px 10px rgba(53, 136, 212, .14); }
@media (max-width: 1000px) { .goals-content { grid-template-columns: 1fr; } }
@media (max-width: 560px) { .goals-list-panel, .detail-panel { padding: 17px; } .goal-top, .detail-head { display: block; } .date-cell { min-height: 54px; padding: 6px; } }
</style>
