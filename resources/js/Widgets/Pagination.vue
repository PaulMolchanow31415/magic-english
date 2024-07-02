<script setup>
import { FwbPagination } from 'flowbite-vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { onKeyStroke } from '@vueuse/core'
import Tooltip from '@/Shared/Tooltip.vue'

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
})

const page = ref(props.data.current_page)

watch(page, (currentPage) => {
  router.visit(props.data.links[currentPage].url, {
    preserveState: true,
  })
})

onKeyStroke('ArrowLeft', () => page.value > 1 && --page.value)

onKeyStroke('ArrowRight', () => page.value < props.data.last_page && ++page.value)
</script>

<template>
  <div v-if="data.prev_page_url || data.next_page_url" class="w-full">
    <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
      <div
        class="flex flex-col items-center justify-between p-4 pe-6 space-y-3 md:flex-row md:space-y-0 md:space-x-4"
      >
        <Tooltip>
          <template #trigger>
            <FwbPagination
              v-model="page"
              :total-pages="data.last_page"
              previous-label="Назад"
              next-label="Вперед"
              show-icons
            />
          </template>
          <template #content>
            <div class="leading-loose flex gap-2.5">
              <Icon :icon="['fas', 'square-caret-left']" />
              <Icon :icon="['fas', 'square-caret-right']" />
            </div>
          </template>
        </Tooltip>
        <span class="text-gray-600 dark:text-gray-400">
          Общее количество:&nbsp;{{ data.total }}
        </span>
      </div>
    </div>
  </div>
</template>
