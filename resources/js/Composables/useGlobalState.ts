import { createGlobalState, useStorage } from '@vueuse/core'

interface IGlobalState {
  song?: {
    audio_url: string
    name: string
    lyrics: string
  }
  singer?: {
    id: number
    poster_url: string
    name: string
  }
  volume: number
}

export const useGlobalState = createGlobalState(() =>
  useStorage('global-state', {
    song: null,
    singer: null,
    volume: 1,
  } as IGlobalState),
)
