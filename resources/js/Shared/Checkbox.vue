<script setup lang="ts">
const model = defineModel<boolean[] | boolean>('checked', {
  default: false,
  required: true,
})

defineProps({ value: String })
</script>

<template>
  <input
    v-model="model"
    type="checkbox"
    :value="value"
    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-700 shadow-sm focus:ring-indigo-500 dark:focus:ring-blue-700 dark:focus:ring-offset-gray-800"
  />
</template>
