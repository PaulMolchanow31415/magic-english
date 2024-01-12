import { computed, warn } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { set } from '@vueuse/core'

export function useQuickEnableRef(ref, timeout = 5000) {
  if (typeof ref !== 'object' || ref.value) {
    warn('1 аргумент принимает только значение ref<any>()')
    return
  }
  set(ref, true)
  setTimeout(() => set(ref, false), timeout)
}

export function useBreadcrumbs() {
  const insertBetween = (items, insertion) => {
    return items.flatMap((value, index, array) =>
      array.length - 1 !== index ? [value, insertion] : value,
    )
  }

  return computed(() => insertBetween(usePage().props.breadcrumbs || [], '/'))
}
