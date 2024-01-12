<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  to: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
  },
  text: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <Link
    :href="to"
    class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
  >
    <Icon
      v-if="icon"
      :icon="['fas', icon]"
      class="w-4 h-4 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
    />
    <span class="ml-3">{{ text }}</span>
  </Link>
</template>