<script setup>
import { FwbButton, FwbModal } from 'flowbite-vue'
import { onKeyPressed, useEventListener } from '@vueuse/core'

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Обновление данных',
  },
  loading: {
    type: Boolean,
    default: false,
  },
  size: {
    type: String,
    default: 'xl',
    validator(v) {
      return (
        v === 'xs' ||
        v === 'sm' ||
        v === 'md' ||
        v === 'lg' ||
        v === 'xl' ||
        v === '2xl' ||
        v === '3xl' ||
        v === '4xl' ||
        v === '5xl' ||
        v === '6xl' ||
        v === '7xl'
      )
    },
  },
})

const emit = defineEmits(['confirm', 'close', 'pressEnter'])

function onKeyPress(event) {
  if (event.shiftKey && event.key === 'Enter') {
    emit('confirm')
  }
}

// без него не получится добавить перевод в Vocabulary.vue
onKeyPressed('Enter', (e) => {
  emit('pressEnter')
  e.preventDefault()
})

useEventListener(document, 'keypress', onKeyPress)
</script>

<template>
  <form @submit.prevent="$emit('confirm')" method="post">
    <FwbModal :size="size" v-show="show" @close="$emit('close')" @click:outside="$emit('close')">
      <template #header>
        <h3 class="pl-2 text-lg font-semibold text-gray-900 dark:text-white">
          {{ title }}
        </h3>
      </template>
      <template #body>
        <slot />
      </template>
      <template #footer>
        <div class="flex items-center gap-4">
          <FwbButton @click="$emit('close')" type="button" color="alternative"> Отмена </FwbButton>
          <FwbButton type="submit" class="flex flex-nowrap" :loading="loading">
            Сохранить
          </FwbButton>
          <p
            class="text-gray-500 dark:text-gray-400 leading-[2.5] line-clamp-1"
            :class="{ 'text-xs': size === 'md' }"
          >
            или <kbd class="ms-1">Shift</kbd> + <kbd>Enter</kbd>
          </p>
        </div>
      </template>
    </FwbModal>
  </form>
</template>

<style scoped></style>