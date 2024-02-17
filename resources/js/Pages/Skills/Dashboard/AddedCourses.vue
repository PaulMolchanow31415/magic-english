<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import CourseList from '@/Pages/Skills/Patials/CourseList.vue'
import { FwbHeading } from 'flowbite-vue'
import FilterPane from '@/Pages/Skills/Patials/FilterPane.vue'

const props = defineProps({
  courses: Array,
  filters: Object,
})
</script>

<template>
  <AutoHead />

  <section>
    <FwbHeading tag="h2" class="mb-10">Изучаемые курсы</FwbHeading>

    <FilterPane :filters="filters" />

    <CourseList :courses="courses" />
  </section>
</template>

<style scoped></style>