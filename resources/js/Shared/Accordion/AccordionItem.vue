<script setup lang="ts">
import { computed, inject, onMounted, ref, watchEffect } from 'vue'
import { ACCORDION_INJECTION_KEY, AccordionProviderPayload } from './types'
import { useHelpers } from '../../Composables'
import FadeHeightTransition from '../Animations/FadeHeightTransition.vue'

const { accordionId, openedItemId, flush, openFirst, alwaysOpen } =
  inject<AccordionProviderPayload>(ACCORDION_INJECTION_KEY)

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

const activeClasses = computed(() => {
  return flush
    ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white'
    : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-white'
})

const inactiveClasses = computed<string>(() => flush && 'text-gray-500 dark:text-gray-400')

const isFirst = computed(() => heading.value.parentElement.children.item(0) === heading.value)

function toggle() {
  open.value = !open.value
  open.value && (openedItemId.value = itemId)
}

if (!alwaysOpen) {
  watchEffect(() => openedItemId.value !== itemId && (open.value = false))
}

onMounted(() => openFirst && isFirst.value && (open.value = true))
</script>

<template>
  <h2 :id="headingId" ref="heading" :class="open ? activeClasses : inactiveClasses">
    <button
      @click="toggle"
      type="button"
      class="aria-expanded:text-gray-800 dark:aria-expanded:text-gray-200 flex items-center justify-between w-full font-medium rtl:text-right text-gray-500 border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3"
      :class="headerClasses"
      :aria-controls="contentId"
      :aria-expanded="open"
    >
      <span>
        <slot name="heading" />
      </span>
      <svg
        class="w-3 h-3 shrink-0 transition"
        :class="{ 'rotate-180': !open }"
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

  <FadeHeightTransition>
    <div v-show="open" :id="contentId" :aria-labelledby="headingId">
      <div :class="contentClasses" class="border-gray-200 dark:border-gray-700">
        <slot />
      </div>
    </div>
  </FadeHeightTransition>
</template>

<style scoped lang="postcss"></style>
