import type { Ref } from 'vue'
import { set } from '@vueuse/core'

export function quickEnableRef(ref: Ref<unknown>, timeout = 5000) {
  set(ref, true)
  setTimeout(() => set(ref, false), timeout)
}
