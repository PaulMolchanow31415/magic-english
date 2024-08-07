<script setup lang="ts">
import { computed, ref } from 'vue'
import { useEventListener } from '@vueuse/core'

type DropdownAlign = 'left' | 'right'
type TWidth = '48'

const props = withDefaults(
  defineProps<{
    align?: DropdownAlign
    width?: TWidth
    contentClasses?: string[]
  }>(),
  {
    align: 'right',
    width: '48',
    contentClasses: () => ['py-1', 'bg-white dark:bg-gray-700'],
  },
)

const open = ref(false)

const widthClass = computed(() => ({ 48: 'w-48' })[props.width.toString()])

const alignmentClasses = computed<string>(() => {
  switch (props.align) {
    case 'left':
      return 'ltr:origin-top-left rtl:origin-top-right start-0'
    case 'right':
      return 'ltr:origin-top-right rtl:origin-top-left end-0'
    default:
      return 'origin-top'
  }
})

useEventListener('keydown', (e: KeyboardEvent) => {
  open.value && e.key === 'Escape' && (open.value = false)
})
</script>

<template>
  <div class="relative" @mouseenter="open = true" @mouseleave="open = false">
    <div>
      <slot name="trigger" />
    </div>

    <Transition
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
        <div class="absolute w-full -z-10" style="height: calc(100% + 1rem); top: -0.5rem" />

        <div class="rounded-md ring-1 ring-black ring-opacity-5" :class="contentClasses">
          <span v-if="$slots.heading" class="block px-4 py-2 text-xs text-gray-400">
            <slot name="heading" />
          </span>
          <slot name="content" />
        </div>
      </div>
    </Transition>
  </div>
</template>
