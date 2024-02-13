import { isRef, warn } from 'vue'
import { set } from '@vueuse/core'

export function useQuickEnableRef(ref, timeout = 5000) {
  if (isRef(ref)) {
    set(ref, true)
    setTimeout(() => set(ref, false), timeout)
    return
  }

  warn('1 аргумент принимает только значение ref<any>()')
}