<script setup lang="ts">
import { computed, inject, onMounted, ref } from 'vue'
import { ACCORDION_INJECTION_KEY, AccordionProviderPayload } from './types'
import { useHelpers } from '../../Composables'

const { accordionId, flush, openFirst } = inject<AccordionProviderPayload>(ACCORDION_INJECTION_KEY)

const itemId = useHelpers().generateRandomId()
const headingId = `${accordionId}-heading-${itemId}`
const contentId = `${accordionId}-content-${itemId}`
const heading = ref<HTMLHeadingElement>()
const open = ref(false)

const headerClasses = computed(() => {
  return flush
    ? 'px-2 py-5 border-b'
    : 'p-5 border border-b-0 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 hover:bg-blue-100 dark:hover:bg-gray-800'
})

const contentClasses = computed(() => {
  return flush ? 'px-1 py-5 border-b' : 'p-5 border border-b-0 dark:bg-gray-900'
})

onMounted(() => {
  // попробовать перенести в глобальный контекст
  if (openFirst && heading.value.parentElement.children.item(0) === heading.value) {
    open.value = true
  }
})
</script>

<template>
  <h2 :id="headingId" ref="heading">
    <button
      @click="open = !open"
      type="button"
      class="aria-expanded:text-gray-800 dark:aria-expanded:text-gray-200 flex items-center justify-between w-full font-medium rtl:text-right text-gray-500 border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3"
      :class="headerClasses"
      :data-accordion-target="`#${contentId}`"
      :aria-controls="contentId"
      :aria-expanded="open"
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

  <div :id="contentId" :class="{ hidden: !open }" :aria-labelledby="headingId">
    <div :class="contentClasses" class="border-gray-200 dark:border-gray-700">
      <slot />
    </div>
  </div>
</template>

<style scoped lang="postcss"></style>
