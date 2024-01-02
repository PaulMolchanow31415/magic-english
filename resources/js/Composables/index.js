import { warn } from 'vue'

export function useShowComponent(ref, timeout = 5000) {
  if (typeof ref !== 'object' || ref.value) {
    warn('1 аргумент принимает только значение ref<any>()')
    return
  }
  ref.value = true
  setTimeout(() => (ref.value = false), timeout)
}