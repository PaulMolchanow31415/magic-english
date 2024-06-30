<script setup lang="ts">
import { FwbBadge } from 'flowbite-vue'
import { FwbBadgeSize, FwbBadgeType } from '../Types'

withDefaults(
  defineProps<{
    icon?: string
    closable?: boolean
    size?: FwbBadgeSize
    theme?: FwbBadgeType
  }>(),
  {
    closable: false,
    theme: 'default',
  },
)

defineEmits(['close'])
</script>

<template>
  <FwbBadge :type="theme" :size="size" class="gap-1.5 whitespace-nowrap items-center">
    <template v-if="icon" #icon>
      <Icon :icon="['fas', icon]" size="sm" />
    </template>

    <template #default>
      <div class="flex items-center">
        <slot />
        <button
          v-if="closable"
          @click="$emit('close')"
          type="button"
          class="inline-flex items-center p-1 ms-2 text-sm text-gray-400 bg-transparent rounded-sm hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-gray-300"
        >
          <Icon :icon="['fas', 'xmark']" size="sm" />
          <span class="sr-only">Закрыть</span>
        </button>
      </div>
    </template>
  </FwbBadge>
</template>
