import { ref } from 'vue'
import { useSpeechSynthesis } from '@vueuse/core'

export function useSay() {
  const vocalizedWord = ref('')

  const { isSupported, stop, isPlaying, speak } = useSpeechSynthesis(vocalizedWord, {
    lang: 'en-US',
    pitch: 1,
    rate: 1,
    volume: 1,
  })

  function say(word: string) {
    vocalizedWord.value = word
    speak()
  }

  return { isSupported, stop, isPlaying, say }
}
