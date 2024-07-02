<script>
import { defineComponent } from 'vue'
import InputError from '@/Shared/InputError.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import OpacityTransition from '@/Shared/Animations/OpacityTransition.vue'

export default defineComponent({
  components: { OpacityTransition, TextRedactor, InputLabel, InputError },

  props: {
    modelValue: {
      type: String,
      required: true,
    },
    error: String,
  },

  emits: ['update:modelValue'],

  parser: new DOMParser(),

  data() {
    return {
      en: '',
      ru: '',
    }
  },

  methods: {
    parse(value, lang) {
      const lines = []
      const wrapper = document.createElement('p')
      const tags = this.$options.parser
        .parseFromString(value, 'text/html')
        .getElementsByTagName('p')

      for (const tag of tags) {
        tag.childNodes.forEach((node) => {
          if (node.textContent) {
            wrapper.innerText = node.textContent
            lang === 'ru' ? (wrapper.className = 'leading-tight opacity-75') : undefined
            lines.push(wrapper.outerHTML)
          }
        })
      }
      return lines
    },
  },

  computed: {
    result() {
      const ruLines = this.parse(this.ru, 'ru')
      const enLines = this.parse(this.en, 'en')
      const result = []

      for (let i = 0; i < enLines.length; i++) {
        result.push(enLines[i], ruLines[i])
      }
      return result.join('')
    },
  },

  watch: {
    result(lyrics) {
      this.$emit('update:modelValue', lyrics)
    },
  },
})
</script>

<template>
  <div>
    <InputLabel value="Слова песни" />

    <div class="flex gap-4 mb-4">
      <TextRedactor v-model="en" toolbar-style="none" placeholder="Оригинал" class="grow" />
      <TextRedactor v-model="ru" toolbar-style="none" placeholder="Перевод" class="grow" />
    </div>

    <OpacityTransition>
      <blockquote
        v-show="modelValue.length"
        v-html="modelValue"
        class="py-4 sm:columns-2 text-center"
      />
    </OpacityTransition>

    <InputError :message="error" />
  </div>
</template>

<style scoped></style>
