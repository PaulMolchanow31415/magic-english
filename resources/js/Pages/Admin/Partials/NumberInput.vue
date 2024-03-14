<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: Number,
  placeholder: String,
  min: {
    type: Number,
    default: 1,
  },
})

const emit = defineEmits(['update:modelValue'])

const proxyValue = computed({
  get: () => props.modelValue,
  set(value) {
    emit('update:modelValue', value)
  },
})
</script>

<template>
  <div class="flex items-center">
    <button
      @click="proxyValue--"
      :disabled="proxyValue <= min"
      type="button"
      class="number-input-button rounded-s-lg"
    >
      <Icon :icon="['fas', 'minus']" class="number-input-icon" />
    </button>
    <input
      v-model="proxyValue"
      type="number"
      aria-describedby="helper-text-explanation"
      class="bg-gray-50 border-x-0 border-gray-300 h-10 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      :placeholder="placeholder"
      :min="min"
    />
    <button @click="proxyValue++" type="button" class="number-input-button rounded-e-lg">
      <Icon :icon="['fas', 'plus']" class="number-input-icon" />
    </button>
  </div>
</template>

<style scoped lang="postcss">
.number-input-button {
  @apply flex items-center bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600
  hover:bg-gray-200 border border-gray-300 p-3 h-10 focus:ring-gray-100
  dark:focus:ring-gray-700 focus:ring-2 focus:outline-none;
}
.number-input-icon {
  @apply w-3 h-3 text-gray-900 dark:text-white;
}
</style>
