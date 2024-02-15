<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import LessonList from '@/Pages/Skills/Patials/LessonList.vue'
import { router } from '@inertiajs/vue3'
import { FwbButton } from 'flowbite-vue'
import FilterSelect from '@/Pages/Skills/Patials/FilterSelect.vue'
import useFilter from '@/Composables/useFilter.js'

const props = defineProps({
  lessons: Array,
  selectedFilter: String,
  filters: Array,
})

const selectedFilter = useFilter('student.lessons.dashboard', props.selectedFilter)
</script>

<template>
  <AutoHead />

  <section
    class="group flex items-center justify-between gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
  >
    <div class="grow">
      <div class="mt-3 flex gap-2.5">
        <FilterSelect v-model="selectedFilter" :filters="filters" />
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

  <LessonList removable :lessons="lessons" />
</template>

<style scoped></style>