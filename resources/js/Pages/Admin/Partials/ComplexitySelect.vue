<script setup lang="ts">
import { SelectOption } from '../../../Classes'
import { FwbOptionsType } from '../../../Types'
import { FwbSelect } from 'flowbite-vue'
import InputLabel from '../../../Shared/InputLabel.vue'
import { usePage } from '@inertiajs/vue3'

withDefaults(
  defineProps<{
    label?: string
    errorMessage?: string
  }>(),
  {
    label: 'Сложность набора',
  },
)

const selected = defineModel({
  type: [null, String],
  required: true,
})

const options = usePage<{
  complexities: string[]
}>().props.complexities.map((c) => new SelectOption({ value: c, name: c })) as FwbOptionsType
</script>

<template>
  <div>
    <InputLabel :value="label">
      <FwbSelect
        v-model="selected"
        placeholder="Выберите сложность"
        size="sm"
        :options="options"
        :validation-status="errorMessage ? 'error' : null"
      >
        <template #validationMessage>{{ errorMessage }} </template>
        <template #helper><slot /></template>
      </FwbSelect>
    </InputLabel>
  </div>
</template>
