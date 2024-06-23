import { Ref, watchEffect } from 'vue'

export function useScrollLock(condRef: Ref<boolean>) {
  watchEffect(() => document.body.classList[condRef.value ? 'add' : 'remove']('overflow-hidden'))
}
