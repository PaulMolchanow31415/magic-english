<script setup>
import { FwbSpinner } from 'flowbite-vue'

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
  processing: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center flex-nowrap gap-1 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
    :class="{ 'opacity-25': processing }"
    :disabled="processing"
  >
    <FwbSpinner v-show="processing" />
    <slot />
  </button>
</template>
