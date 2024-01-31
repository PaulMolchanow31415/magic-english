<script setup>
defineEmits(['close'])
</script>

<template>
  <button
    @click="$emit('close')"
    aria-label="Закрыть"
    class="border-none ml-auto bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex justify-center items-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
    type="button"
  >
    <span class="sr-only">Закрыть</span>
    <Icon :icon="['fas', 'xmark']" size="lg" />
  </button>
</template>

<style scoped></style>
