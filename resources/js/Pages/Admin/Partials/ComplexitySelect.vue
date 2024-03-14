<script setup>
import SelectOption from '@/Classes/SelectOption.js'
import { FwbSelect } from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { usePage } from '@inertiajs/vue3'

defineProps({
  modelValue: {
    type: [null, String],
    required: true,
  },
  label: {
    type: String,
    default: 'Сложность набора',
  },
  errorMessage: {
    type: String,
  },
})

defineEmits(['update:modelValue'])

const options = usePage().props.complexities.map((c) => new SelectOption({ value: c, name: c }))
</script>

<template>
  <div>
    <InputLabel :value="label">
      <FwbSelect
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
        placeholder="Выберите сложность"
        size="sm"
        :options="options"
        :validation-status="errorMessage ? 'error' : ''"
      >
        <template #validationMessage>{{ errorMessage }} </template>
        <template #helper><slot /></template>
      </FwbSelect>
    </InputLabel>
  </div>
</template>
