<script setup>
import { computed } from 'vue'

const props = defineProps({
  value: [String, Object],
  name: String,
  modelValue: [String, Object],
  label: String,
  disabled: Boolean,
  status: {
    type: String,
    validator: (v) => v === 'success' || v === 'error',
  },
})

const emit = defineEmits(['update:modelValue'])

const proxyChecked = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const labelClasses = computed(() => {
  if (props.status === 'success') {
    return 'has-[:checked]:ring-green-500 has-[:checked]:text-green-900 has-[:checked]:bg-green-50 dark:has-[:checked]:ring-green-700 dark:has-[:checked]:text-green-300 dark:has-[:checked]:bg-gray-950'
  }
  if (props.status === 'error') {
    return 'has-[:checked]:ring-red-500 has-[:checked]:text-red-900 has-[:checked]:bg-red-50 dark:has-[:checked]:ring-red-700 dark:has-[:checked]:text-red-300 dark:has-[:checked]:bg-gray-950'
  }
})

const icon = computed(() => {
  if (!props.status) {
    return ['far', 'circle']
  }
  if (props.status === 'success') {
    return ['fas', 'circle-check']
  }
  if (props.status === 'error') {
    return ['fas', 'circle-xmark']
  }
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
      v-model="proxyChecked"
      type="radio"
      :value="value"
      :name="name"
      :disabled="disabled"
      class="hidden"
    />
  </label>
</template>

<style scoped></style>
