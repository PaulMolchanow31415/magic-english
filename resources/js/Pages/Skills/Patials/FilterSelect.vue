<script setup>
import SelectOption from '@/Types/SelectOption.ts'
import { FwbSelect } from 'flowbite-vue'

const model = defineModel({ type: String })
</script>

<template>
  <FwbSelect
    v-model="model"
    :options="$page.props.learnableFilters.map((f) => new SelectOption({ value: f, name: f }))"
    size="sm"
    placeholder="Фильтр"
    class="max-w-fit"
  />
</template>
