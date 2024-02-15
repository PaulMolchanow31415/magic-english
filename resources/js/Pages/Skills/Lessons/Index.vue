<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import { router } from '@inertiajs/vue3'
import { FwbButton, FwbHeading } from 'flowbite-vue'
import LessonList from '@/Pages/Skills/Patials/LessonList.vue'

defineProps({
  lessons: Array,
  learnableLessonsCount: Number,
})
</script>

<template>
  <AutoHead />

  <section
    @click.stop="router.visit(route('student.lessons.dashboard'))"
    v-if="learnableLessonsCount > 0"
    class="group dashboard-cta-section"
  >
    <Icon
      :icon="['fas', 'graduation-cap']"
      size="3x"
      class="text-gray-700 group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-white"
    />
    <div class="grow">
      <FwbHeading tag="h4" class="group-hover:text-blue-600 dark:group-hover:text-white">
        Изучаемые уроки
      </FwbHeading>
    </div>
    <FwbButton size="lg" gradient="cyan" class="dashboard-cta-button">Перейти</FwbButton>
  </section>

  <LessonList :lessons="lessons" />
</template>

<style scoped></style>