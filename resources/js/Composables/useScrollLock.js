import { isRef, warn, watchEffect } from 'vue'

/** @param condRef {{ value: boolean }} */
export function useScrollLock(condRef) {
  if (!isRef(condRef)) {
    warn('Первый аргумент функции - ref<boolean>')
  }

  watchEffect(() => document.body.classList[condRef.value ? 'add' : 'remove']('overflow-hidden'))
}
