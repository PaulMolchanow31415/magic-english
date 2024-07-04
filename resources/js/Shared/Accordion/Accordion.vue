<script setup lang="ts">
import { computed, onMounted, provide } from 'vue'
import { ACCORDION_INJECTION_KEY, AccordionProviderPayload } from './types'
import { initAccordions } from 'flowbite'
import { useHelpers } from '../../Composables'

const props = defineProps<{
  alwaysOpen?: boolean
  openFirst?: boolean
  flush?: boolean
}>()

const accordionId = `accordion-${useHelpers().generateRandomId(4)}`

const activeClasses = computed<string>(() => {
  return props.flush
    ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white'
    : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-white'
})

const inactiveClasses = computed<string>(() => props.flush && 'text-gray-500 dark:text-gray-400')

// const context = ref<HTMLCollection>()
// const wrapper = ref<HTMLDivElement>()

// сначала монтируются дети, потом родитель
onMounted(() => {
  initAccordions()
  // context.value = wrapper.value.children
})

provide<AccordionProviderPayload>(ACCORDION_INJECTION_KEY, {
  accordionId,
  flush: props.flush,
  openFirst: props.openFirst,
})
</script>

<template>
  <div
    ref="wrapper"
    :id="accordionId"
    :data-accordion="alwaysOpen ? 'open' : 'collapse'"
    :data-active-classes="activeClasses"
    :data-inactive-classes="inactiveClasses"
    class="border-gray-200 dark:border-gray-700"
    :class="{ 'border-b [&>*:first-child>button]:rounded-t-xl': !flush }"
  >
    <slot />
  </div>
</template>

<style scoped></style>
