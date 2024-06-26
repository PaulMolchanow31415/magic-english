import { ComputedRef, watchEffect } from 'vue'

export function useScrollLock(condRef: ComputedRef<unknown>) {
  watchEffect(() => document.body.classList[condRef.value ? 'add' : 'remove']('overflow-hidden'))
}
