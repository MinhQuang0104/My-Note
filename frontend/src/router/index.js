import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'
import HomePage from '../components/HomePage.vue'
import AuthView from '../views/AuthView.vue'
import CalendarView from '../views/CalendarView.vue'
import GoalsView from '../views/GoalsView.vue'
import NotesView from '../views/NotesView.vue'

const routes = [
  {
    path: '/',
    component: AppLayout,
    children: [
      { path: '', name: 'home', component: HomePage },
      { path: 'notes', name: 'notes', component: NotesView },
      { path: 'goals', name: 'goals', component: GoalsView },
      { path: 'calendar', name: 'calendar', component: CalendarView },
    ],
  },
  {
    path: '/login',
    name: 'login',
    component: AuthView,
    props: { mode: 'login' },
  },
  {
    path: '/register',
    name: 'register',
    component: AuthView,
    props: { mode: 'register' },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
