import { ref } from 'vue'
import { set, useSpeechSynthesis } from '@vueuse/core'

export function useSay() {
  const vocalizedWord = ref('')

  const { isSupported, stop, isPlaying, speak } = useSpeechSynthesis(vocalizedWord, {
    lang: 'en-US',
    pitch: 1,
    rate: 1,
    volume: 1,
  })

  function say(word) {
    set(vocalizedWord, word)
    speak()
  }

  return { isSupported, stop, isPlaying, say }
}
