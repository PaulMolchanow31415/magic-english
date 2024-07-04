<script setup lang="ts">
import { onMounted } from 'vue'
import { initCollapses } from 'flowbite'
import { useHelpers } from '../../Composables'

defineProps<{ text: string }>()

onMounted(initCollapses)

const id = useHelpers().generateRandomId()
</script>

<template>
  <button
    type="button"
    class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
    :aria-controls="id"
    :data-collapse-toggle="id"
  >
    <Icon
      :icon="['fas', 'list']"
      class="flex-shrink-0 w-4 h-4 text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
    />
    <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ text }}</span>
    <Icon :icon="['fas', 'angle-down']" />
  </button>
  <ul :id="id" class="hidden py-2 space-y-2">
    <slot />
  </ul>
</template>
