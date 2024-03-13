<script setup>
import { onClickOutside, set } from '@vueuse/core'
import { ref } from 'vue'
import { FwbButton } from 'flowbite-vue'
import Toast from '@/Classes/Toast.js'
import Toaster from '@/Shared/Toaster.vue'
import { router } from '@inertiajs/vue3'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'
import OpacitySlideTopTransition from '@/Animations/OpacitySlideTopTransition.vue'

const wrapper = ref(null)
const srcWord = ref('')
const translations = ref([])
const popover = ref(null)
const isAdded = ref(false)
const isError = ref(false)

async function translateOrHide(event) {
  const selection = getSelection()
  const range = selection.getRangeAt(0)
  const text = selection.anchorNode.textContent
  let word
  let startIndex
  let endIndex
  let currentIndex = range.startOffset

  hide()

  while (typeof startIndex !== 'number') {
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

  word = text.slice(startIndex, endIndex).replaceAll(/[^a-zA-Z\s]+/g, '')

  if (!word || word.match(/^[А-я]+$/)) {
    return
  }

  const res = await axios.get(route('api.translate', { word }))

  if (res.data.length === 0) {
    return
  }

  popover.value.style.top = event.pageY + 'px'
  popover.value.style.left = event.pageX + 'px'

  set(srcWord, word)
  set(translations, res.data.length > 4 ? res.data.slice(0, 5) : res.data.slice(0, res.data.length))
}

function learn() {
  router.post(route('student.add-vocabulary', { word: srcWord.value }), null, {
    onSuccess: () => {
      hide()
      useQuickEnableRef(isAdded, 1600)
    },
    onError: () => useQuickEnableRef(isError, 1600),
    preserveScroll: true,
  })
}

function hide() {
  set(srcWord, '')
  set(translations, [])
}

onClickOutside(wrapper, hide, { ignore: [popover] })
</script>

<template>
  <div>
    <Toaster
      :tosts="[
        new Toast({ type: 'success', isShow: isAdded, value: 'Слово добавлено!' }),
        new Toast({ type: 'warning', isShow: isError, value: 'Не удалось добавить в словарь' }),
      ]"
    />

    <div @click="translateOrHide" ref="wrapper">
      <slot />
    </div>

    <Teleport to="body">
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
