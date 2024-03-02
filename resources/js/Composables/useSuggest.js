import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import SuggestListItem from '@/Classes/SuggestListItem.js'

export default function useSuggest(routeName, propName = 'en') {
  const searched = ref('')
  const results = ref([])

  watchThrottled(
    searched,
    (value) =>
      axios.get(route(routeName, { search: value || 'a' })).then((res) => {
        results.value = res.data.data.map((v) => {
          return new SuggestListItem({ id: v.id, value: v[propName] })
        })
      }),
    { throttle: 300 },
  )

  return { searched, results }
}
