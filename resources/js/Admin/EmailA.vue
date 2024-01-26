<script setup>
import { FwbA } from 'flowbite-vue'

defineProps({
  email: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <FwbA
    :href="`mailto:${email}`"
    class="hover:underline hover:text-blue-600 dark:hover:text-blue-400"
  >
    {{ email }}
  </FwbA>
</template>

<style scoped></style>