import type { Ref } from 'vue'

export function quickEnableRef(ref: Ref<boolean>, timeout = 5000) {
  ref.value = true
  setTimeout(() => (ref.value = false), timeout)
}
