<script setup>
import { router } from '@inertiajs/vue3'
import { FwbButton, FwbCard, FwbP } from 'flowbite-vue'

defineProps({ courses: Array })
</script>

<template>
  <div
    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-3 gap-y-4"
  >
    <FwbCard
      @click.stop="router.visit(route('skills.course.show', { name: course.name }))"
      v-for="course in courses"
      :img-alt="course.name"
      :img-src="course.poster_url"
      class="card min-h-44"
      :title="course.name"
    >
      <div class="p-3">
        <h5
          class="text-lg leading-tight font-semibold tracking-tight text-gray-900 dark:text-white line-clamp-1 mb-2"
          v-text="course.name"
        />
        <FwbP
          v-text="course.description"
          :title="course.description"
          class="leading-tight hyphens-auto line-clamp-2"
        />
        <FwbButton type="button" color="alternative" size="sm" class="w-full mt-3">
          Изучить
        </FwbButton>
      </div>
    </FwbCard>
  </div>
</template>

<style scoped></style>