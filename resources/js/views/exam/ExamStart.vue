<template>
    <div class="container my-5">
      <div class="exam-wrapper p-4 p-md-5 bg-white shadow rounded">
        <h1 class="mb-4 text-center text-orange fw-bold fs-3">
          {{ exam?.title }}
        </h1>
  
        <div class="alert alert-warning text-center fw-semibold fs-5 mb-5">
          ⏳ Time Remaining: {{ formattedTime }}
        </div>
  
        <div v-for="(q, i) in exam?.questions" :key="q.id" class="mb-5">
          <div class="mb-2 fw-medium">
            <h3>
              {{ i + 1 }}. {{ q.question_text }}
            </h3>
          </div>
  
          <div v-for="opt in q.options" :key="opt.id" class="form-check ms-3 mb-2">
            <input
              class="form-check-input fs-4"
              type="radio"
              :id="'opt-' + opt.id"
              :name="'question_' + q.id"
              :value="opt.id"
              v-model="answers[q.id]"
            />
            <label class="form-check-label fs-4" :for="'opt-' + opt.id">
              {{ opt.option_text }}
            </label>
          </div>
        </div>
  
        <div class="text-center mt-5">
          <button @click="submitExam" class="btn btn-lg btn-orange px-5">
            Submit Exam
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios'
  
  const route = useRoute()
  const router = useRouter()
  const exam = ref(null)
  const answers = ref({})
  const timeLeft = ref(0)
  let timer = null
  
  // Format: X min Y sec
  const formattedTime = computed(() => {
    const minutes = Math.floor(timeLeft.value / 60)
    const seconds = timeLeft.value % 60
    return `${minutes} min ${seconds < 10 ? '0' : ''}${seconds} sec`
  })
  
  onMounted(async () => {
    const res = await axios.get(`/api/exams/${route.params.id}`)
    exam.value = res.data
    timeLeft.value = exam.value.duration * 60 // seconds
  
    timer = setInterval(() => {
      timeLeft.value--
      if (timeLeft.value <= 0) {
        clearInterval(timer)
        alert('Your time is over! Please contact authority.')
      }
    }, 1000) // Every second
  })
  
  onBeforeUnmount(() => {
    clearInterval(timer)
  })
  
  async function submitExam() {
    if (timeLeft.value <= 0) {
      alert('Your time is over! Please contact authority.')
      return
    }
  
    try {
      const res = await axios.post(`/api/exams/${exam.value.id}/submit`, {
        answers: answers.value,
      })
      router.push({ name: 'ExamResult', query: { result_id: res.data.result_id } })
    } catch (err) {
      alert('Failed to submit. Try again.')
    }
  }
  </script>
  
  <style scoped>
  .exam-wrapper {
    border-left: 6px solid #fd7e14;
    background-color: #fffdf9;
  }
  
  .text-orange {
    color: #fd7e14;
  }
  
  .btn-orange {
    background-color: #fd7e14;
    color: white;
    border: none;
    transition: background-color 0.3s ease;
  }
  
  .btn-orange:hover {
    background-color: #e96c0a;
  }
  </style>
  