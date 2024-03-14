<script setup>
defineProps({
  theme: {
    type: String,
    default: 'blue',
    validator(v) {
      return v === 'blue' || v === 'red' || v === 'green'
    },
  },
})
</script>

<template>
  <button
    type="button"
    class="hover:underline"
    :class="{
      'text-red-600 dark:text-red-400': theme === 'red',
      'text-blue-600 dark:text-blue-400': theme === 'blue',
      'text-green-600 dark:text-green-400': theme === 'green',
    }"
  >
    <slot />
  </button>
</template>
