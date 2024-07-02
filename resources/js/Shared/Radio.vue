<script setup lang="ts">
import { computed } from 'vue'
import { FwbInput } from 'flowbite-vue'

type FwbValidationStatus = typeof FwbInput.__defaults.validationStatus

const props = defineProps<{
  value?: string | object
  name?: string
  label?: string
  disabled?: boolean
  status?: FwbValidationStatus
}>()

const checked = defineModel<string | object>()

const labelClasses = computed<string>(() => {
  return props.status === 'success'
    ? 'has-[:checked]:ring-green-500 has-[:checked]:text-green-900 has-[:checked]:bg-green-50 dark:has-[:checked]:ring-green-700 dark:has-[:checked]:text-green-300 dark:has-[:checked]:bg-gray-950'
    : 'has-[:checked]:ring-red-500 has-[:checked]:text-red-900 has-[:checked]:bg-red-50 dark:has-[:checked]:ring-red-700 dark:has-[:checked]:text-red-300 dark:has-[:checked]:bg-gray-950'
})

const icon = computed(() => {
  if (!props.status) {
    return ['far', 'circle']
  }
  return props.status === 'success' ? ['fas', 'circle-check'] : ['fas', 'circle-xmark']
})
</script>

<template>
  <label
    v-wave
    :class="labelClasses"
    class="text-slate-700 dark:text-slate-200 grid grid-cols-[24px_1fr_auto] items-center gap-6 rounded-lg p-4 ring-1 ring-slate-100 dark:ring-slate-500 hover:bg-slate-100 dark:hover:bg-slate-900 select-none cursor-pointer"
  >
    <Icon :icon="icon" class="w-8" />

    {{ label }}

    <input
      v-model="checked"
      type="radio"
      :value="value"
      :name="name"
      :disabled="disabled"
      class="hidden"
    />
  </label>
</template>

<style scoped></style>
