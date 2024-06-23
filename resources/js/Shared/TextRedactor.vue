<script setup>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { ref } from 'vue'
import { set } from '@vueuse/core'
import InputError from '@/Shared/InputError.vue'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Start typing here...',
  },
  breakLineKeyBinding: {
    type: String,
    default: 'Enter', // instead Shift+Enter
  },
  errorMessage: String,
  toolbarStyle: {
    type: String,
    default: 'default',
    validator(v) {
      return v === 'full' || v === 'minimal' || v === 'default' || v === 'none'
    },
  },
  disabled: Boolean,
})

const model = defineModel({ type: String })
const editor = ref()
let toolbar = ref({})

switch (props.toolbarStyle) {
  case 'default':
    // prettier-ignore
    set(toolbar, [
      'undo', 'redo',
      '|', 'heading',
      '|', 'bold', 'italic',
      // '|', 'link', 'uploadImage', 'blockQuote',
      // '|', 'bulletedList', 'numberedList', 'outdent', 'indent',
    ])
    break
  case 'minimal':
    set(toolbar, ['undo', 'redo', '|', 'bold', 'italic'])
    break
  case 'none':
    set(toolbar, ['undo', 'redo'])
    break
}

function onReady(readyEditor) {
  readyEditor.keystrokes.set(props.breakLineKeyBinding, 'enter')
  set(editor, readyEditor)
}
</script>

<template>
  <div class="no-tailwindcss">
    <Ckeditor
      @ready="onReady"
      v-model="model"
      :editor="ClassicEditor"
      :config="{ placeholder, toolbar }"
      tag-name="textarea"
      :disabled="disabled"
    />
    <InputError :message="errorMessage" />
  </div>
</template>

<style scoped></style>
