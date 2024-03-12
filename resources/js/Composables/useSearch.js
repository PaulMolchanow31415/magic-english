import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import { router } from '@inertiajs/vue3'

export function useSearch(searchValue) {
  const searched = ref(searchValue || '')

  watchThrottled(
    searched,
    (search) =>
      search &&
      router.get(
        route(route().current()),
        { search },
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
