<script setup>
import { ref } from 'vue'
import { useEventListener } from '@vueuse/core'

const model = defineModel({
  type: String,
  required: true,
})
const input = ref(null)

defineProps({
  placeholder: {
    type: String,
    default: 'Поиск...',
  },
})

useEventListener(document, 'keydown', (e) => {
  const code = e.which || e.keyCode

  // click ctrl + k
  if (e.ctrlKey && code === 75) {
    e.preventDefault()
    input.value.focus()
  }
})
</script>

<template>
  <div class="flex relative w-full">
    <div
      class="w-10 flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none overflow-hidden"
    >
      <Icon class="text-gray-700 dark:text-gray-300" :icon="['fas', 'magnifying-glass']" />
    </div>
    <input
      ref="input"
      v-model="model"
      class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2 text-sm pl-10"
      :placeholder="placeholder"
      type="text"
    />
    <div class="absolute right-2.5 bottom-2.5">
      <kbd class="p-1">Ctrl</kbd>&nbsp;+&nbsp;<kbd class="p-1 px-1.5">K</kbd>
    </div>
  </div>
</template>
