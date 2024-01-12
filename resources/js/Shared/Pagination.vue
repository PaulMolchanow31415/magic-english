<script setup>
import { FwbPagination } from 'flowbite-vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
})

const currentPage = ref(props.data.current_page)

watch(currentPage, (updated) => {
  router.visit(props.data.links[updated].url, {
    preserveState: true,
  })
})
</script>

<template>
  <div class="w-full">
    <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
      <div
        class="flex flex-col items-center justify-between p-4 pe-6 space-y-3 md:flex-row md:space-y-0 md:space-x-4"
      >
        <FwbPagination
          v-model="currentPage"
          :total-pages="data.last_page"
          previous-label="Назад"
          next-label="Вперед"
          show-icons
          large
        />
        <span class="text-gray-600 dark:text-gray-400">
          Общее количество:&nbsp;{{ data.total }}
        </span>
      </div>
    </div>
  </div>
</template>

<style scoped></style>