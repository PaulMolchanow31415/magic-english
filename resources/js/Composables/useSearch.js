import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import { router } from '@inertiajs/vue3'

export function useSearch(searchValue) {
  const searched = ref(searchValue || '')

  watchThrottled(
    searched,
    (value) =>
      router.get(
        route(route().current()),
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
