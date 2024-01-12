<script setup>
defineProps({
  message: String,
})
</script>

<template>
  <div v-show="message">
    <small class="mt-2 text-sm text-red-600 dark:text-red-500">
      {{ message }}
    </small>
  </div>
</template>
