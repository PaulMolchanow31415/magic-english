import { createGlobalState, useStorage } from '@vueuse/core'

export const useFlashMessageState = createGlobalState(() =>
  useStorage<{
    messages: []
    isEmpty: () => boolean
  }>('flash-messages', {
    messages: [],

    isEmpty() {
      return !this.messages.length
    },
  }),
)
