<script setup>
import { onMounted } from 'vue'
import { initTooltips } from 'flowbite'

defineProps({
  placement: {
    type: String,
    default: 'top',
    validator(v) {
      return v === 'bottom' || v === 'top' || v === 'left' || v === 'right'
    },
  },
  /*theme: {
    type: String,
    default: 'dark',
    validator(v) {
      return v === 'dark' || v === 'light'
    },
  },*/
})

onMounted(() => {
  initTooltips()
})

const uuid = crypto.randomUUID()
</script>

<template>
  <div>
    <div :data-tooltip-target="uuid" :data-tooltip-placement="placement">
      <slot name="trigger" />
    </div>

    <div
      :id="uuid"
      role="tooltip"
      class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
    >
      <div>
        <slot name="content" />
      </div>
      <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
  </div>
</template>

<style scoped></style>