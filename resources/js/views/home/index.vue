<template>
  <div class="container my-5">
    <h1 class="text-center mb-4 fw-bold text-orange">Available Exams</h1>

    <div class="row g-4">
      <div
        v-for="exam in exams"
        :key="exam.id"
        class="col-12 col-sm-6 col-md-4"
      >
        <div
          class="card h-100 shadow border-0 exam-card"
          @click="startExam(exam.id)"
        >
          <div class="card-body text-center">
            <h5 class="card-title fw-bold text-orange">{{ exam.title }}</h5>
            <p class="card-text text-muted mb-2">
              ⏱ Duration: {{ exam.duration }} min
            </p>
            <p class="card-text text-muted">❓ Questions: {{ exam.questions_count }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const exams = ref([])
const router = useRouter()

onMounted(async () => {
  const res = await axios.get('/api/exams')
  exams.value = res.data
})

function startExam(id) {
  router.push({ name: 'ExamStart', params: { id } })
}
</script>

<style scoped>
.text-orange {
  color: #fd7e14;
}

.exam-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background-color: #fff;
  border-left: 5px solid #fd7e14;
  cursor: pointer;
}
.exam-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0 20px rgba(253, 126, 20, 0.2);
  background-color: #fff8f0;
}
</style>
