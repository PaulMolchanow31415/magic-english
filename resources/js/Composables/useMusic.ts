import { createGlobalState, useStorage } from '@vueuse/core'
import { computed } from 'vue'

interface Song {
  audio_url: string
  name: string
  lyrics: string
}

interface Singer {
  id: number
  poster_url: string
  name: string
}

const state = createGlobalState(() =>
  useStorage<{
    song?: Song
    singer?: Singer
    volume?: number
  }>('music-state', {
    song: null,
    singer: null,
    volume: 1,
  }),
)()

export function useMusic() {
  return {
    setSong(song?: Song) {
      state.value.song = song
    },
    setSinger(singer?: Singer) {
      state.value.singer = singer
    },
    clear() {
      state.value.song = null
      state.value.singer = null
    },
    setVolume(volume?: number) {
      state.value.volume = volume
    },
    isPresent: computed(() => !!(state.value.song && state.value.singer)),
    isMuted: computed(() => !state.value.volume),
    volume: computed(() => state.value.volume),
    singer: computed(() => state.value.singer),
    song: computed(() => state.value.song),
  }
}
