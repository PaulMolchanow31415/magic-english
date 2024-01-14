<script>
import { defineComponent } from 'vue'
import { FwbBadge } from 'flowbite-vue'

export default defineComponent({
  components: { FwbBadge },
  props: {
    closable: {
      type: Boolean,
      default: false,
    },
    icon: {
      type: String,
    },
    size: {
      type: String,
      validator(v) {
        return v === 'xs' || v === 'sm' || v === 'lg'
      },
    },
    theme: {
      /* 'default' | 'dark' | 'red' | 'green' | 'yellow' | 'indigo' | 'purple' | 'pink'> */
      type: String,
    },
  },
  emits: ['close'],
})
</script>

<template>
  <FwbBadge :type="theme || 'default'" :size="size" class="gap-1.5 items-center">
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

<style scoped></style>