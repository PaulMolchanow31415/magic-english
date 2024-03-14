<script setup>
import { FwbA } from 'flowbite-vue'

defineProps({
  mail: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <FwbA
    :href="`mailto:${mail}`"
    class="hover:underline hover:text-blue-600 dark:hover:text-blue-400"
  >
    {{ mail }}
  </FwbA>
</template>
