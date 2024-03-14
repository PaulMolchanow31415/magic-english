<script>
import { defineComponent } from 'vue'
import InputError from '@/Shared/InputError.vue'

export default defineComponent({
  components: { InputError },
  emits: ['update:modelValue'],

  props: {
    modelValue: {
      type: Array,
      required: true,
    },
    errorMessage: String,
  },

  beforeUpdate() {
    this.phonetics.length = 0
  },

  data() {
    return {
      source: '',
      translation: '',
      phonetics: [],
    }
  },

  methods: {
    setPhoneticsRef(el) {
      if (el) {
        this.phonetics.push(el)
      }
    },
    update(focusOnIndex) {
      this.$emit('update:modelValue', [
        ...this.modelValue,
        {
          source: this.source,
          translation: this.translation,
        },
      ])
      this.source = ''
      this.translation = ''
      this.$nextTick(() => {
        this.phonetics.at(-1).children[focusOnIndex].focus()
      })
    },
    onDelete(index) {
      this.modelValue.splice(index, 1)
      this.$emit('update:modelValue', this.modelValue)
      this.$refs.sourceInput.focus()
    },
  },

  watch: {
    source(value) {
      if (this.translation.length > 0 && value.length > 0) {
        this.update(0)
      }
    },
    translation(value) {
      if (this.source.length > 0 && value.length > 0) {
        this.update(1)
      }
    },
  },
})
</script>

<template>
  <div class="space-y-2">
    <div
      v-for="(phonetics, index) in modelValue"
      :key="index"
      :ref="setPhoneticsRef"
      class="flex gap-2.5 items-end relative group"
    >
      <input
        v-model="phonetics.source"
        type="text"
        class="input-underlined"
        placeholder="Текст на английском"
      />
      <input
        v-model="phonetics.translation"
        type="text"
        class="input-underlined"
        placeholder="Перевод"
      />
      <button
        @click="onDelete(index)"
        type="button"
        class="opacity-0 group-hover:opacity-100 absolute right-0 bottom-[2px] flex items-center py-2 px-3 border border-b-0 border-gray-300 text-gray-300 bg-transparent dark:text-gray-600 dark:border-gray-600 dark:focus:border-blue-500 focus:text-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600"
      >
        <Icon :icon="['fas', 'minus']" />
      </button>
    </div>
    <div class="flex gap-2.5 items-center">
      <input
        ref="sourceInput"
        v-model="source"
        type="text"
        class="input-underlined"
        placeholder="Текст на английском"
      />
      <input v-model="translation" type="text" class="input-underlined" placeholder="Перевод" />
    </div>
    <InputError :message="errorMessage" />
  </div>
</template>
