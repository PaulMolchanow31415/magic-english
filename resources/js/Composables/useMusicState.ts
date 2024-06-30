import { createGlobalState, useStorage } from '@vueuse/core'

export const useMusicState = createGlobalState(() =>
  useStorage<{
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
  }>('music-state', {
    song: null,
    singer: null,
    volume: 1,
  }),
)
