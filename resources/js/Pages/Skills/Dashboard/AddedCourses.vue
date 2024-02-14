<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import CourseList from '@/Pages/Skills/Patials/CourseList.vue'
import { FwbHeading } from 'flowbite-vue'

const props = defineProps({ courses: Array })
</script>

<template>
  <AutoHead />

  <section>
    <FwbHeading tag="h2" class="mb-10">Изучаемые курсы</FwbHeading>
    <CourseList :courses="courses" />
  </section>
</template>

<style scoped></style>