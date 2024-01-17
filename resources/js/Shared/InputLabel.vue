<script setup>
defineProps({
  value: String,
  color: {
    type: String,
    default: 'text-gray-500',
  },
})
</script>

<template>
  <label>
    <span v-if="value" class="block mb-2 text-sm font-medium dark:text-white" :class="color">
      {{ value }}
    </span>
    <slot />
  </label>
</template>
