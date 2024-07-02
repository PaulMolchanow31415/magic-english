<script setup lang="ts">
import { computed, inject, onMounted } from 'vue'
import { ACCORDION_INJECTION_KEY, AccordionProviderPayload } from './types'
import { nanoid } from 'nanoid'
import { initAccordions } from 'flowbite'

const props = defineProps<{ order?: number; openFirst?: boolean }>()

const { accordionId, flush } = inject<AccordionProviderPayload>(ACCORDION_INJECTION_KEY)

const itemOrder = props.order ?? nanoid()
const headingId = `${accordionId}-heading-${itemOrder}`
const contentId = `${accordionId}-content-${itemOrder}`

const headerClasses = computed(() => {
  return flush
    ? 'px-2 py-5 border-b'
    : 'p-5 border border-b-0 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 hover:bg-blue-100 dark:hover:bg-gray-800'
})

const contentClasses = computed(() => {
  return flush ? 'px-1 py-5 border-b' : 'p-5 border border-b-0 dark:bg-gray-900'
})

onMounted(initAccordions)
</script>

<template>
  <h2 :id="headingId">
    <button
      type="button"
      class="aria-expanded:text-gray-800 dark:aria-expanded:text-gray-200 flex items-center justify-between w-full font-medium rtl:text-right text-gray-500 border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3"
      :class="[!flush && itemOrder === 0 && 'rounded-t-xl', headerClasses]"
      :data-accordion-target="`#${contentId}`"
      :aria-controls="contentId"
      :aria-expanded="openFirst ? itemOrder === 0 : false"
    >
      <span>
        <slot name="heading" />
      </span>
      <svg
        data-accordion-icon
        class="w-3 h-3 rotate-180 shrink-0"
        aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 10 6"
      >
        <path
          stroke="currentColor"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 5 5 1 1 5"
        />
      </svg>
    </button>
  </h2>

  <div :id="contentId" class="hidden" :aria-labelledby="headingId">
    <div :class="contentClasses" class="border-gray-200 dark:border-gray-700">
      <slot />
    </div>
  </div>
</template>

<style scoped lang="postcss"></style>
