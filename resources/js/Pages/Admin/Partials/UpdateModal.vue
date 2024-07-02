<script setup lang="ts">
import { FwbButton, FwbModal } from 'flowbite-vue'
import { onKeyPressed, useEventListener } from '@vueuse/core'
import Opacity300Transition from '../../../Shared/Animations/Opacity300Transition.vue'

// prettier-ignore
withDefaults(defineProps<{
  show?: boolean
  title?: string
  loading?: boolean
  size?: typeof FwbModal.__defaults.size
}>(), {
  show: false,
  title: 'Обновление данных',
  loading: false,
  size: 'xl',
})

const emit = defineEmits(['confirm', 'close', 'pressEnter'])

// без него не получится добавить перевод в Vocabulary.vue
onKeyPressed('Enter', (e) => {
  emit('pressEnter')
  e.preventDefault()
})

useEventListener(document, 'keypress', (event: KeyboardEvent) => {
  if (event.shiftKey && event.key === 'Enter') {
    emit('confirm')
  }
})
</script>

<template>
  <form @submit.prevent="$emit('confirm')" method="post">
    <Opacity300Transition>
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
            <FwbButton @click="$emit('close')" type="button" color="alternative">
              Отмена
            </FwbButton>
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
    </Opacity300Transition>
  </form>
</template>

<style scoped></style>
