import { ref } from 'vue'
import { Toast } from '../Entities'

interface ShowOptions {
  closable?: boolean
  timeout?: number
}

const state = ref<Toast[]>([])

export function useFlashMessages(globOptions?: ShowOptions) {
  return {
    flashMessages: state,

    showMessage(
      message: string,
      type = 'info' as typeof Toast.prototype.type,
      options = globOptions,
    ) {
      const toast = new Toast({ value: message, type, closable: options?.closable })
      const index = state.value.push(toast) - 1
      // prettier-ignore
      const id = setTimeout(() => {
        state.value.splice(index, 1)
        clearTimeout(id)
      }, options?.timeout ?? 5000)
    },

    closeMessage(index: number) {
      state.value.splice(index, 1)
    },
  }
}
