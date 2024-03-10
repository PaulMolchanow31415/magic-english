import { createGlobalState, useStorage } from '@vueuse/core'

export const useGlobalState = createGlobalState(() =>
  useStorage('global-state', {
    song: null,
    singer: null,
    volume: 1,
  }),
)
