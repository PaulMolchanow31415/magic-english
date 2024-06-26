<script setup>
import SelectOption from '@/Types/SelectOption.ts'
import { FwbSelect } from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { usePage } from '@inertiajs/vue3'

defineProps({
  label: {
    type: String,
    default: 'Сложность набора',
  },
  errorMessage: { type: String },
})

const selected = defineModel({
  type: [null, String],
  required: true,
})

const options = usePage().props.complexities.map((c) => new SelectOption({ value: c, name: c }))
</script>

<template>
  <div>
    <InputLabel :value="label">
      <FwbSelect
        v-model="selected"
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
