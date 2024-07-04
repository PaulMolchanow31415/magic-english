<script setup lang="ts">
import { provide, ref } from 'vue'
import { ACCORDION_INJECTION_KEY, AccordionProps, AccordionProviderPayload } from './types'
import { useHelpers } from '../../Composables'

const props = defineProps<AccordionProps>()

const accordionId = `accordion-${useHelpers().generateRandomId(4)}`

const openedItemId = ref<string>()

provide<AccordionProviderPayload>(ACCORDION_INJECTION_KEY, {
  accordionId,
  openedItemId,
  flush: props.flush,
  openFirst: props.openFirst,
  alwaysOpen: props.alwaysOpen,
})
</script>

<template>
  <div
    :id="accordionId"
    class="border-gray-200 dark:border-gray-700"
    :class="{ 'border-b [&>*:first-child>button]:rounded-t-xl': !flush }"
  >
    <slot />
  </div>
</template>

<style scoped></style>
