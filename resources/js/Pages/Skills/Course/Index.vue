<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import { router } from '@inertiajs/vue3'
import { FwbButton, FwbHeading, FwbP } from 'flowbite-vue'
import { ref, toRef } from 'vue'
import useInfiniteScrollLoader from '@/Composables/useInfiniteScrollLoader.js'
import CourseList from '@/Pages/Skills/Patials/CourseList.vue'

const props = defineProps({
  courses: Object,
  learnableCoursesCount: Number,
})

const courses = toRef(props, 'courses')

const list = ref(null)

const { allItems } = useInfiniteScrollLoader(list, courses)
</script>

<template>
  <AutoHead />

  <section
    @click.stop="router.visit(route('student.courses.dashboard'))"
    v-if="learnableCoursesCount > 0"
    class="group dashboard-cta-section"
  >
    <Icon
      :icon="['fas', 'scroll']"
      size="3x"
      class="text-gray-700 group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-white"
    />
    <div class="grow">
      <FwbHeading tag="h4" class="group-hover:text-blue-600 dark:group-hover:text-white">
        Изучаемые курсы
      </FwbHeading>
      <FwbP class="group-hover:text-blue-600 dark:text-gray-600 dark:group-hover:text-white">
        {{ learnableCoursesCount }} курсов
      </FwbP>
    </div>
    <FwbButton size="lg" gradient="green-blue" class="dashboard-cta-button"> Повторить </FwbButton>
  </section>

  <div ref="list">
    <CourseList :courses="allItems" />
  </div>
</template>

<style scoped></style>
