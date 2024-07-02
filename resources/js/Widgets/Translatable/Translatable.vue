<script setup lang="ts">
import { onClickOutside } from '@vueuse/core'
import { reactive, ref } from 'vue'
import { FwbButton, FwbSpinner } from 'flowbite-vue'
import { router } from '@inertiajs/vue3'
import OpacitySlideTopTransition from '../../Shared/Animations/OpacitySlideTopTransition.vue'
import Opacity300Transition from '../../Shared/Animations/Opacity300Transition.vue'
import { Spinner } from '../../Entities'
import { translate } from './api'
import { useFlashMessages } from '../../Composables'

const { showMessage } = useFlashMessages({ timeout: 1600 })
const wrapper = ref<HTMLDivElement>(null)
const srcWord = ref('')
const translations = ref<string[]>([])
const popover = ref<HTMLDivElement>(null)
const isLoading = ref(false)
const spinnerStyles = reactive(new Spinner())

async function translateOrHide(event: MouseEvent) {
  const selection = getSelection()
  const range = selection.getRangeAt(0)
  const text = selection.anchorNode.textContent
  let word: string
  let startIndex: number
  let endIndex: number
  let currentIndex = range.startOffset

  hide()

  if (currentIndex === text.length) {
    return
  }

  isLoading.value = true
  spinnerStyles.top = `${event.pageY}px`
  spinnerStyles.left = `${event.pageX}px`

  while (!startIndex && typeof startIndex !== 'number') {
    if (text[currentIndex] === ' ') {
      startIndex = currentIndex - 1
    } else if (currentIndex === -1) {
      startIndex = 0
    } else if (text[--currentIndex] === ' ') {
      startIndex = currentIndex + 1
    }
  }

  currentIndex = range.startOffset

  while (!endIndex) {
    if (text[currentIndex] === ' ') {
      endIndex = currentIndex - 1
    } else if (currentIndex + 1 === text.length) {
      endIndex = text.length
    } else if (text[++currentIndex] === ' ') {
      endIndex = currentIndex
    }
  }

  word = text.slice(startIndex, endIndex).replace(/[^a-zA-Z\s]+/g, '')

  if (!word || word.match(/^[А-я]+$/)) {
    isLoading.value = false
    return
  }

  const data = await translate(word)

  isLoading.value = false

  if (data.length === 0) {
    return
  }

  popover.value.style.top = event.pageY + 'px'
  popover.value.style.left = event.pageX + 'px'

  srcWord.value = word
  translations.value = data.length > 4 ? data.slice(0, 5) : data.slice(0, data.length)
}

function learn() {
  router.post(route('student.add-vocabulary', { word: srcWord.value }), null, {
    onSuccess: () => {
      hide()
      showMessage('Слово добавлено!', 'success')
    },
    onError: () => showMessage('Не удалось добавить в словарь', 'warning'),
    preserveScroll: true,
  })
}

function hide() {
  srcWord.value = ''
  translations.value.length = 0
}

onClickOutside(wrapper, hide, { ignore: [popover] })
</script>

<template>
  <div>
    <div @click="translateOrHide" ref="wrapper">
      <slot />
    </div>

    <Teleport to="body">
      <Opacity300Transition>
        <FwbSpinner :style="spinnerStyles" v-show="isLoading" size="10" />
      </Opacity300Transition>

      <OpacitySlideTopTransition>
        <div
          v-show="srcWord"
          ref="popover"
          style="transform: translate(-50%, calc(-100% - 1rem))"
          role="tooltip"
          class="z-50 absolute inline-block w-64 text-sm text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800"
        >
          <div
            class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700"
          >
            <div class="flex justify-between items-center">
              <h3 class="font-semibold text-gray-900 dark:text-white">{{ srcWord }}</h3>
              <!-- Close button -->
              <button @click="hide" type="button" class="shrink-0">
                <Icon :icon="['fas', 'circle-xmark']" />
              </button>
            </div>
          </div>
          <div class="px-3 py-2">
            <ul class="mb-4 leading-normal list-disc pl-6">
              <li v-for="t in translations">{{ t }}</li>
            </ul>
            <!-- Challenges button -->
            <FwbButton
              @click="learn"
              type="button"
              class="w-full items-center gap-2.5 px-3 py-2 text-xs"
              size="sm"
            >
              Выучить
            </FwbButton>
          </div>
        </div>
      </OpacitySlideTopTransition>
    </Teleport>
  </div>
</template>

<style scoped></style>
