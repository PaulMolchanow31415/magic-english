<script setup lang="ts">
import { computed, provide } from 'vue'
import { ACCORDION_INJECTION_KEY, AccordionProviderPayload } from './types'
import { nanoid } from 'nanoid'

const props = defineProps<{ alwaysOpen?: boolean; flush?: boolean }>()

const accordionId = `accordion-${nanoid(4)}`

const activeClasses = computed<string>(() => {
  return props.flush
    ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white'
    : 'bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white'
})

const inactiveClasses = computed<string>(() => props.flush && 'text-gray-500 dark:text-gray-400')

provide<AccordionProviderPayload>(ACCORDION_INJECTION_KEY, { accordionId, flush: props.flush })
</script>

<template>
  <div
    :id="accordionId"
    :data-accordion="alwaysOpen ? 'open' : 'collapse'"
    :data-active-classes="activeClasses"
    :data-inactive-classes="inactiveClasses"
    class="border-gray-200 dark:border-gray-700"
    :class="{ 'border-b': !flush }"
  >
    <slot />
  </div>
</template>

<style scoped></style>
