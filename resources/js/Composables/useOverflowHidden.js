import { isRef, warn, watchEffect } from 'vue'

/** @param condRef {{ value: boolean }} */
export function useOverflowHidden(condRef) {
  if (!isRef(condRef)) {
    warn('Первый аргумент функции useOverflowHidden - ref<boolean>')
  }

  watchEffect(() => document.body.classList[condRef.value ? 'add' : 'remove']('overflow-hidden'))
}
