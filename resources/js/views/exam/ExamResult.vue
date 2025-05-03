<template>
  <div class="max-w-4xl mx-auto p-6" v-if="result">
    <div class="bg-[#fffdf9] rounded-xl shadow-lg p-8 border-l-8 border-[#fd7e14]">
      <h1 class="text-4xl font-extrabold text-[#fd7e14] mb-4 text-center">🎓 Exam Result</h1>
      <p class="text-2xl font-bold text-green-700 mb-8 text-center fs-4">
        ✅ Total Score: {{ result.score }}
      </p>

      <div v-for="item in result.details" :key="item.question_id" class="mb-8">
        <div class="bg-white rounded-lg p-6 shadow border border-orange-200">
          <p class="text-xl font-semibold text-gray-800 mb-4 text-center fs-3">
            ❓ {{ item.question_text }}
          </p>

          <div class="ml-4">
            <p
              class="text-lg mb-2 flex items-center gap-2 font-semibold  text-center fs-4"
              :class="item.is_correct ? 'text-green-700' : 'text-red-600'"
            >
              Your Answer:
              <span
                class="px-3 py-1 rounded text-base text-center fs-4"
                :class="item.is_correct ? 'bg-green-100' : 'bg-red-100'"
              >
                {{ item.your_answer || 'Not Answered' }}
              </span>
            </p>

            <p class="text-lg text-green-700 font-semibold text-center fs-4">
              ✅ Correct Answer:
              <span class="bg-green-100 px-3 py-1 rounded text-base text-center fs-4">
                {{ item.correct_answer }}
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="text-center text-gray-600 py-16 text-xl">
    ⏳ Loading result...
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const result = ref(null)

onMounted(async () => {
  const resultId = route.query.result_id
  if (!resultId) {
    router.push({ name: 'ExamList' })
    return
  }

  try {
    const res = await axios.get(`/api/results/${resultId}`)
    result.value = res.data
  } catch (err) {
    alert('Failed to load result.')
    router.push({ name: 'ExamList' })
  }
})
</script>
