import type { Ref } from 'vue'
import { set } from '@vueuse/core'

export function quickEnableRef(ref: Ref<boolean>, timeout = 5000) {
  set(ref, true)
  setTimeout(() => set(ref, false), timeout)
}
