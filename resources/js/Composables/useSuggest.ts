import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import { RouteName } from '../../../vendor/tightenco/ziggy'
import { SuggestListItem } from '../Entities'
import { ISuggestion } from '../Core/Types'

async function loadSuggestions(name: RouteName, search: string) {
  const { data } = await axios.get<ISuggestion>(route(name, { search }))
  return data
}

export function useSuggest(routeName: RouteName, propName = 'en') {
  const searched = ref('')
  const results = ref<SuggestListItem[]>([])

  watchThrottled(
    searched,
    (search) =>
      search &&
      loadSuggestions(routeName, search).then((res) => {
        results.value = res.data.map((item) => {
          return new SuggestListItem({ id: item.id, value: item[propName] })
        })
      }),
    { throttle: 300 },
  )

  return { searched, results }
}
