<script setup>
import { computed, onMounted } from 'vue'
import { initTooltips } from 'flowbite'

const props = defineProps({
  placement: {
    type: String,
    default: 'top',
    validator(v) {
      return v === 'bottom' || v === 'top' || v === 'left' || v === 'right'
    },
  },
  theme: {
    type: String,
    default: 'dark',
    validator(v) {
      return v === 'dark' || v === 'light'
    },
  },
})

onMounted(() => {
  initTooltips()
})

const classes = computed(() => {
  if (props.theme === 'dark') {
    return 'text-white bg-gray-900 dark:bg-gray-700'
  }
  if (props.theme === 'light') {
    return 'text-gray-900 bg-white border border-gray-200'
  }
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
      class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium transition-opacity duration-300 rounded-lg shadow-sm opacity-0 tooltip"
      :class="classes"
    >
      <div>
        <slot name="content" />
      </div>
      <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
  </div>
</template>
