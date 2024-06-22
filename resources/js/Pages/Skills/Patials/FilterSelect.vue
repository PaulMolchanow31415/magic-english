<script setup>
import SelectOption from '@/Types/SelectOption.js'
import { FwbSelect } from 'flowbite-vue'

defineProps({ modelValue: String })

defineEmits(['update:modelValue'])
</script>

<template>
  <FwbSelect
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    :options="$page.props.learnableFilters.map((f) => new SelectOption({ value: f, name: f }))"
    size="sm"
    placeholder="Фильтр"
    class="max-w-fit"
  />
</template>
