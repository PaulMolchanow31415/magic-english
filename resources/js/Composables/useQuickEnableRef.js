import { warn } from 'vue'
import { set } from '@vueuse/core'

export function useQuickEnableRef(ref, timeout = 5000) {
  if (typeof ref !== 'object' || ref.value) {
    warn('1 аргумент принимает только значение ref<any>()')
    return
  }
  set(ref, true)
  setTimeout(() => set(ref, false), timeout)
}