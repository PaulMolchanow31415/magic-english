<script setup>
import Datepicker from 'flowbite-datepicker/Datepicker'
import { onMounted, ref } from 'vue'

const props = defineProps({
  format: {
    type: String,
    default: 'mm/dd/yyyy',
  },
  placeholder: {
    type: String,
    default: 'Выберите дату',
  },
})

const datepicker = ref(null)

onMounted(() => {
  new Datepicker(datepicker.value, {
    language: 'ru',
    todayHighlight: true,
    format: props.format,
  })
})

defineExpose({ focus: () => datepicker.value.focus() })
</script>

<template>
  <div class="relative">
    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
      <Icon :icon="['fas', 'calendar-days']" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
    </div>
    <input
      ref="datepicker"
      datepicker
      type="text"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      :placeholder="placeholder"
    />
  </div>
</template>
