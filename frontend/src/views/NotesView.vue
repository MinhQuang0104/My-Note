<script setup>
import { computed, ref } from 'vue'

const notes = ref([
  { id: 1, title: 'On tap Vue Composition API', content: 'Cac diem can nho khi dung ref, computed va composable trong du an.', tag: 'Frontend', time: '10 phut truoc' },
  { id: 2, title: 'Y tuong cho Project My-Note', content: 'Tach luong ghi chu, muc tieu va lich thanh cac nhip lam viec ro rang.', tag: 'Productivity', time: 'Hom qua' },
  { id: 3, title: 'Checklist truoc khi commit', content: 'Kiem tra luong dang nhap, trang thai loading va loi API.', tag: 'Dev', time: '16 thang 8' },
  { id: 4, title: 'Learning reflection - tuan 33', content: 'Dieu gi da hieu ro hon? Dieu gi can quay lai?', tag: 'Reflection', time: '15 thang 8' },
])

const goals = ref([
  { id: 1, title: 'Theo doi uong nuoc hang ngay', logs: [] },
  { id: 2, title: 'On tap Vue Composition API', logs: [] },
  { id: 3, title: 'Doc sach ky thuat', logs: [] },
])

const search = ref('')
const selectedTag = ref('All')
const selectedId = ref(1)
const showModal = ref(false)
const showToast = ref(false)
const dragNote = ref(null)
const newNote = ref({ title: '', content: '', tag: 'Frontend' })

const selectedNote = computed(() => notes.value.find((note) => note.id === selectedId.value) || notes.value[0])
const filteredNotes = computed(() =>
  notes.value.filter((note) => {
    const haystack = `${note.title} ${note.content}`.toLowerCase()
    return haystack.includes(search.value.toLowerCase()) && (selectedTag.value === 'All' || note.tag === selectedTag.value)
  }),
)

function flashToast() {
  showToast.value = true
  window.setTimeout(() => {
    showToast.value = false
  }, 2200)
}

function createNote() {
  const note = { id: Date.now(), title: newNote.value.title, content: newNote.value.content, tag: newNote.value.tag, time: 'Vua tao' }
  notes.value.unshift(note)
  selectedId.value = note.id
  newNote.value = { title: '', content: '', tag: 'Frontend' }
  showModal.value = false
  flashToast()
}

function dropToGoal(goal) {
  if (!dragNote.value) return
  goal.logs.unshift({ title: dragNote.value.title, content: dragNote.value.content })
  dragNote.value = null
  flashToast()
}
</script>

<template>
  <header class="topbar">
    <div>
      <p class="eyebrow">Workspace / Ghi chu</p>
      <h1>Ghi chu</h1>
      <p>Thu thap, sap xep va quay lai nhung dieu quan trong.</p>
    </div>
    <button class="primary" type="button" @click="showModal = true">+ Ghi chu moi</button>
  </header>

  <div class="toolbar panel">
    <input v-model="search" class="search" placeholder="Tim trong ghi chu..." />
    <select v-model="selectedTag">
      <option value="All">Tat ca tag</option>
      <option>Frontend</option>
      <option>Productivity</option>
      <option>Reflection</option>
      <option>Dev</option>
    </select>
    <select>
      <option>Moi cap nhat</option>
      <option>Cu nhat</option>
    </select>
  </div>

  <div class="notes-layout">
    <section class="panel">
      <div class="panel-head">
        <h2 class="ornament" data-icon="*">Tat ca ghi chu</h2>
        <span class="muted">{{ filteredNotes.length }} ghi chu</span>
      </div>
      <div class="note-list">
        <button
          v-for="note in filteredNotes"
          :key="note.id"
          class="note"
          :class="{ active: note.id === selectedId }"
          draggable="true"
          type="button"
          @click="selectedId = note.id"
          @dragstart="dragNote = note"
        >
          <strong>{{ note.title }}</strong>
          <p>{{ note.content }}</p>
          <small>{{ note.time }} - <span class="tag">{{ note.tag }}</span></small>
        </button>
      </div>
    </section>

    <section v-if="selectedNote" class="panel editor-panel">
      <input v-model="selectedNote.title" aria-label="Tieu de ghi chu" />
      <textarea v-model="selectedNote.content" aria-label="Noi dung ghi chu"></textarea>
      <div class="editor-meta">
        <span>Da cap nhat {{ selectedNote.time }} - Tu dong luu</span>
        <div class="actions">
          <button class="secondary" type="button" @click="flashToast">Luu ban nhap</button>
          <button class="secondary danger" type="button">Xoa</button>
        </div>
      </div>
      <div class="tip">Keo mot ghi chu sang muc tieu ben phai de them vao phan hoan thanh hom nay.</div>
    </section>

    <aside class="panel goals-panel">
      <div class="panel-head stacked">
        <h2 class="ornament pink" data-icon="o">Muc tieu</h2>
        <span class="goal-help">Keo ghi chu vao muc tieu tuong ung</span>
      </div>
      <div class="goal-list">
        <div v-for="goal in goals" :key="goal.id" class="goal-drop" @dragover.prevent @drop="dropToGoal(goal)">
          <h3>{{ goal.title }}</h3>
          <span class="drop-hint">Tha ghi chu vao day</span>
          <div class="completion-log">
            <span v-if="goal.logs.length === 0" class="empty-log">Chua co ghi chu hom nay</span>
            <article v-for="log in goal.logs" :key="log.title" class="completion-note">
              <strong>{{ log.title }}</strong>
              <p>{{ log.content }}</p>
            </article>
          </div>
        </div>
      </div>
    </aside>
  </div>

  <div v-if="showModal" class="modal-backdrop" @click.self="showModal = false">
    <section class="modal panel">
      <div class="modal-head">
        <div>
          <h2>Tao ghi chu moi</h2>
          <p>Luu nhanh mot y tuong hoac dieu vua hoc.</p>
        </div>
        <button class="modal-close" type="button" @click="showModal = false">x</button>
      </div>
      <form class="modal-form" @submit.prevent="createNote">
        <label class="modal-label">Tieu de<input v-model="newNote.title" class="field" required /></label>
        <label class="modal-label">Noi dung<textarea v-model="newNote.content" class="field" required></textarea></label>
        <label class="modal-label">Tag<select v-model="newNote.tag" class="field"><option>Frontend</option><option>Productivity</option><option>Reflection</option><option>Khac</option></select></label>
        <div class="modal-actions">
          <button class="secondary" type="button" @click="showModal = false">Huy</button>
          <button class="primary" type="submit">Luu ghi chu</button>
        </div>
      </form>
    </section>
  </div>

  <div class="toast" :class="{ show: showToast }">Da cap nhat ghi chu.</div>
</template>

<style scoped>
.notes-layout { display: grid; grid-template-columns: minmax(220px, .72fr) minmax(420px, 1.25fr) minmax(235px, .73fr); gap: 14px; align-items: start; }
.panel-head { display: flex; justify-content: space-between; align-items: center; padding: 17px 18px; border-bottom: 1px solid theme('colors.line'); }
.panel-head h2 { margin: 0; font-size: 16px; }
.stacked { display: block; }
.note-list { padding: 8px; }
.note { display: block; width: 100%; padding: 14px 12px; border: 0; border-bottom: 1px solid theme('colors.line'); background: #fff; color: theme('colors.ink'); text-align: left; cursor: grab; }
.note:last-child { border: 0; }
.note.active, .note:hover { border-radius: 7px; background: theme('colors.blue.100'); }
.note strong { display: block; font-size: 14px; }
.note p { margin: 6px 0 10px; color: theme('colors.muted'); font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.note small { color: #92a5b3; }
.tag { padding: 3px 8px; border-radius: 99px; background: theme('colors.blue.100'); color: theme('colors.blue.700'); font-size: 11px; font-weight: 600; }
.editor-panel { padding: 24px; }
.editor-panel input, .editor-panel textarea { display: block; width: 100%; border: 0; outline: 0; background: transparent; color: theme('colors.ink'); }
.editor-panel input { margin-bottom: 16px; font-size: 24px; font-weight: 700; }
.editor-panel textarea { min-height: 300px; padding-top: 18px; border-top: 1px solid theme('colors.line'); line-height: 1.8; resize: vertical; }
.editor-meta { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding-top: 15px; border-top: 1px solid theme('colors.line'); color: theme('colors.muted'); font-size: 12px; }
.actions { display: flex; gap: 8px; }
.tip { margin-top: 16px; padding: 13px 15px; border-left: 3px solid theme('colors.yellow'); background: #fff8e9; color: #83672e; font-size: 13px; }
.goal-help { color: theme('colors.muted'); font-size: 12px; }
.goal-list { display: grid; gap: 9px; padding: 10px; }
.goal-drop { padding: 13px; border: 1px solid theme('colors.line'); border-radius: 8px; background: #fbfdff; }
.goal-drop:hover { border-color: theme('colors.blue.500'); background: theme('colors.blue.100'); }
.goal-drop h3 { margin: 0; font-size: 14px; }
.drop-hint, .empty-log { display: block; margin-top: 7px; color: #9aabb7; font-size: 11px; }
.completion-log { display: grid; gap: 6px; margin-top: 10px; }
.completion-note { padding: 8px 9px; border-left: 3px solid theme('colors.green'); border-radius: 4px; background: #edf9f4; font-size: 11px; }
.completion-note strong { color: theme('colors.green'); }
.pink::before { background: #fff0f3; color: #e98b9f; box-shadow: 0 2px 0 #f3c3ce, 0 5px 10px rgba(53, 136, 212, .14); }
@media (max-width: 1120px) { .notes-layout { grid-template-columns: minmax(210px, .7fr) minmax(390px, 1.3fr); } .goals-panel { grid-column: 1 / -1; } .goal-list { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 820px) { .notes-layout { grid-template-columns: 1fr; } .goal-list { grid-template-columns: 1fr 1fr; } }
@media (max-width: 560px) { .goal-list { grid-template-columns: 1fr; } .editor-meta { display: block; } }
</style>
