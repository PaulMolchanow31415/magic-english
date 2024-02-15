<script setup>
import SelectOption from '@/Classes/SelectOption.js'
import { FwbSelect } from 'flowbite-vue'

defineProps({
  modelValue: String,
  filters: Array,
})

defineEmits(['update:modelValue'])
</script>

<template>
  <FwbSelect
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    :options="filters.map((f) => new SelectOption({ value: f, name: f }))"
    size="sm"
    placeholder="Фильтр"
    class="max-w-fit"
  />
</template>

<style scoped></style>