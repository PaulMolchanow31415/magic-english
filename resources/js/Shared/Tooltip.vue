<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { initTooltips } from 'flowbite'
import { TypeTheme } from '../Types'
import { nanoid } from 'nanoid'

type TooltipPlacement = 'bottom' | 'top' | 'left' | 'right'

const props = withDefaults(
  defineProps<{
    placement?: TooltipPlacement
    theme?: TypeTheme
  }>(),
  {
    placement: 'top',
    theme: 'dark',
  },
)

const id = nanoid()

const classes = computed(() =>
  props.theme === 'dark'
    ? 'text-white bg-gray-900 dark:bg-gray-700'
    : 'text-gray-900 bg-white border border-gray-200',
)

onMounted(initTooltips)
</script>

<template>
  <div>
    <div :data-tooltip-target="id" :data-tooltip-placement="placement">
      <slot name="trigger" />
    </div>

    <div
      :id="id"
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
