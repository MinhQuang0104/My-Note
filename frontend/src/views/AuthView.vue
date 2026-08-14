<script setup>
import { ref } from 'vue'
import { useAuth } from '../composables/useAuth'

const props = defineProps({
  mode: { type: String, default: 'login' },
})

const { register, login } = useAuth()

const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const loading = ref(false)
const message = ref(null)

async function submit() {
  loading.value = true
  message.value = null
  try {
    if (props.mode === 'login') {
      await login({ email: form.value.email, password: form.value.password })
      message.value = 'Đăng nhập thành công'
    } else {
      await register({
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation,
      })
      message.value = 'Đăng ký thành công'
    }
    form.value.password = ''
    form.value.password_confirmation = ''
  } catch (err) {
    message.value = (err && err.message) || JSON.stringify(err)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <section class="panel">
    <h2>{{ props.mode === 'login' ? 'Đăng nhập' : 'Đăng ký' }}</h2>

    <div v-if="props.mode !== 'login'">
      <label>Name</label>
      <input v-model="form.name" placeholder="Họ tên" />
    </div>

    <label>Email</label>
    <input v-model="form.email" placeholder="email@example.com" />

    <label>Password</label>
    <input type="password" v-model="form.password" />

    <div v-if="props.mode !== 'login'">
      <label>Confirm Password</label>
      <input type="password" v-model="form.password_confirmation" />
    </div>

    <div style="margin-top:8px">
      <button class="primary" @click.prevent="submit" :disabled="loading">
        {{ props.mode === 'login' ? 'Đăng nhập' : 'Tạo tài khoản' }}
      </button>
    </div>

    <p v-if="message" style="margin-top:8px">{{ message }}</p>
  </section>
</template>
