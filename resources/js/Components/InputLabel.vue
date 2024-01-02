<script setup>
defineProps({ value: String })
</script>

<template>
  <label>
    <span v-if="value" class="block mb-2 text-sm font-medium text-gray-500 dark:text-white">
      {{ value }}
    </span>
    <slot />
  </label>
</template>
