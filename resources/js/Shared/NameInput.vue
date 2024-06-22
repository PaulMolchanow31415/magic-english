<script setup>
import { FwbInput } from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'

const [model] = defineModel({
  set(value) {
    return value
    .split(' ')
    .map((word) =>
      word.length === 1
        ? word.toLowerCase()
        : word.charAt(0).toUpperCase() + word.slice(1).toLowerCase(),
    )
    .join(' ')
  },
})

defineProps({
  label: {
    type: String,
    default: 'Название',
  },
  errorMessage: String,
})
</script>

<template>
  <InputLabel :value="label">
    <FwbInput v-model="model" :validation-status="errorMessage ? 'error' : ''">
      <template #validationMessage v-text="errorMessage" />
    </FwbInput>
  </InputLabel>
</template>

<style scoped></style>
