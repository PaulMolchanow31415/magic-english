<script setup>
import { computed, ref } from 'vue'
import { useEventListener } from '@vueuse/core'

const props = defineProps({
  align: {
    type: String,
    default: 'right',
  },
  width: {
    type: String,
    default: '48',
  },
  contentClasses: {
    type: Array,
    default: () => ['py-1', 'bg-white dark:bg-gray-700'],
  },
})

const open = ref(false)

const widthClass = computed(() => {
  return {
    48: 'w-48',
  }[props.width.toString()]
})

const alignmentClasses = computed(() => {
  if (props.align === 'left') {
    return 'ltr:origin-top-left rtl:origin-top-right start-0'
  }

  if (props.align === 'right') {
    return 'ltr:origin-top-right rtl:origin-top-left end-0'
  }

  return 'origin-top'
})

const closeOnEscape = (e) => {
  if (open.value && e.key === 'Escape') {
    open.value = false
  }
}

useEventListener('keydown', closeOnEscape)
</script>

<template>
  <div class="relative" @mouseenter="open = true" @mouseleave="open = false">
    <div>
      <slot name="trigger" />
    </div>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="open"
        class="absolute z-50 mt-2 rounded-md shadow-lg"
        :class="[widthClass, alignmentClasses]"
        style="display: none"
        @click="open = false"
      >
        <!-- Overlay -->
        <div class="absolute w-full -z-10" style="height: calc(100% + 1rem); top: -1rem" />

        <div class="rounded-md ring-1 ring-black ring-opacity-5" :class="contentClasses">
          <slot name="content" />
        </div>
      </div>
    </transition>
  </div>
</template>
