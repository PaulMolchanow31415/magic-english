<script setup lang="ts">
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { ref } from 'vue'
import InputError from '../Shared/InputError.vue'

type ToolbarStyle = 'full' | 'minimal' | 'default' | 'none'

const props = withDefaults(
  defineProps<{
    placeholder?: string
    breakLineKeyBinding?: string
    errorMessage?: string
    toolbarStyle?: ToolbarStyle
    disabled?: boolean
  }>(),
  {
    placeholder: 'Начните печатать здесь...',
    breakLineKeyBinding: 'Enter', // instead Shift+Enter
    toolbarStyle: 'default',
  },
)

const model = defineModel<string>()
const editor = ref()
let toolbar = ref<string[]>()

switch (props.toolbarStyle) {
  case 'default':
    // prettier-ignore
    toolbar.value = [
      'undo', 'redo',
      '|', 'heading',
      '|', 'bold', 'italic',
      // '|', 'link', 'uploadImage', 'blockQuote',
      // '|', 'bulletedList', 'numberedList', 'outdent', 'indent',
    ]
    break
  case 'minimal':
    toolbar.value = ['undo', 'redo', '|', 'bold', 'italic']
    break
  case 'none':
    toolbar.value = ['undo', 'redo']
    break
}

interface ReadyEvent {
  keystrokes: { set: (binding: string, key: string) => void }
}

function onReady(readyEditor: ReadyEvent) {
  readyEditor.keystrokes.set(props.breakLineKeyBinding, 'enter')
  editor.value = readyEditor
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
