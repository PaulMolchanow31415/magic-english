import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import SuggestListItem from '../Types/SuggestListItem'
import { RouteName } from '../../../vendor/tightenco/ziggy'

export function useSuggest(routeName: RouteName, propName = 'en') {
  const searched = ref('')
  const results = ref<SuggestListItem[]>([])

  watchThrottled(
    searched,
    (search) =>
      search &&
      axios.get(route(routeName, { search })).then((res) => {
        results.value = res.data.data.map((item: { id: number }) => {
          return new SuggestListItem({ id: item.id, value: item[propName] })
        })
      }),
    { throttle: 300 },
  )

  return { searched, results }
}
