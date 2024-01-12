import { computed, ref, warn } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { set } from '@vueuse/core'
import { watchThrottled } from '@vueuse/core'

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

export function useSearch(searchValue, routeName) {
  const searched = ref(searchValue || '')

  watchThrottled(
    searched,
    (value) =>
      router.get(
        route(routeName),
        { search: `${value}` },
        {
          preserveState: true,
          preserveScroll: true,
          replace: true,
        },
      ),
    { throttle: 500 },
  )
  return searched
}
