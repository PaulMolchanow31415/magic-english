import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import { RouteName } from '../../../vendor/tightenco/ziggy'
import { SuggestListItem } from '../Classes'
import { getSuggestions } from '../api'

export function useSuggest(routeName: RouteName, propName = 'en') {
  const searched = ref('')
  const results = ref<SuggestListItem[]>([])

  watchThrottled(
    searched,
    (search) =>
      search &&
      getSuggestions(routeName, search).then((res) => {
        results.value = res.data.map((item) => {
          return new SuggestListItem({ id: item.id, value: item[propName] })
        })
      }),
    { throttle: 300 },
  )

  return { searched, results }
}
