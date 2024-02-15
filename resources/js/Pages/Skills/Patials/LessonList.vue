<script setup>
import { Link, router } from '@inertiajs/vue3'
import { FwbButton, FwbHeading } from 'flowbite-vue'

defineProps({
  lessons: Array,
  removable: Boolean,
})

function remove(lesson) {
  router.delete(route('student.remove-lesson', { number: lesson.number }), {
    preserveScroll: true,
    preserveState: true,
  })
}
</script>

<template>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
    <template v-if="removable">
      <div
        v-for="lesson in lessons"
        :key="lesson.number"
        class="lesson-list-item !py-1 !flex items-center justify-between gap-2.5"
      >
        <Link :href="route('skills.lesson.show', { number: lesson.number })" class="grow py-2">
          Урок {{ lesson.number }}
        </Link>
        <FwbButton
          @click="remove(lesson)"
          type="button"
          color="alternative"
          class="hover:text-red-600"
          size="sm"
        >
          <Icon :icon="['fas', 'trash-can']" size="sm" />
        </FwbButton>
      </div>
    </template>

    <template v-else>
      <Link
        v-for="lesson in lessons"
        :key="lesson.number"
        class="lesson-list-item"
        :href="route('skills.lesson.show', { number: lesson.number })"
      >
        Урок {{ lesson.number }}
      </Link>
    </template>
  </div>

  <FwbHeading v-if="!lessons.length" tag="h5" class="text-center mt-6">
    Здесь пока ничего нет
  </FwbHeading>
</template>

<style scoped>
.lesson-list-item {
  @apply block py-2 px-4 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-300
  dark:border-gray-700 text-gray-700 dark:text-white transition duration-75 hover:bg-gray-100
  dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400;
}
</style>