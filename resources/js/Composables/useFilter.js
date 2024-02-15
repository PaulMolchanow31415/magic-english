import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

export default function (routeName, initialValue) {
  const selectedFilter = ref(initialValue)

  watch(selectedFilter, (filter, oldFilterValue) => {
    if (oldFilterValue !== filter) {
      router.visit(route(routeName, { filter }), {
        preserveScroll: true,
      })
    }
  })

  return selectedFilter
}