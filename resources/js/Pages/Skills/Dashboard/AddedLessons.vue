<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import LessonList from '@/Pages/Skills/Patials/LessonList.vue'
import { router } from '@inertiajs/vue3'
import { FwbButton } from 'flowbite-vue'
import FilterPane from '@/Pages/Skills/Patials/FilterPane.vue'

const props = defineProps({
  lessons: Array,
  selectedFilter: String,
  filters: Object,
})
</script>

<template>
  <AutoHead />

  <section
    class="group flex items-center justify-between gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
  >
    <div class="grow">
      <FwbButton
        @click="router.delete(route('student.flush-lessons'), { preserveScroll: true })"
        size="sm"
        color="alternative"
        type="button"
        class="py-2"
      >
        <Icon :icon="['far', 'trash-can']" class="me-1" />
        Удалить все уроки
      </FwbButton>
    </div>

    <FwbButton
      @click="router.visit(route('student.latest-added-lesson'))"
      size="lg"
      gradient="cyan-blue"
      type="button"
      class="md:px-36 md:py-3 font-bold px-12 me-4"
    >
      Перейти к последнему
    </FwbButton>
  </section>

  <FilterPane :filters="filters" />

  <LessonList removable :lessons="lessons" />
</template>

<style scoped></style>