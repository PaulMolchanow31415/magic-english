import { ref } from 'vue'
import { watchThrottled } from '@vueuse/core'
import SuggestListItem from '@/Classes/SuggestListItem.js'

export default function useSuggest(routeName) {
  const searched = ref('')
  const results = ref([])

  watchThrottled(
    searched,
    (value) =>
      axios.get(route(routeName, { search: value || 'a' })).then((res) => {
        results.value = res.data.data.map((v) => new SuggestListItem({ id: v.id, value: v.en }))
      }),
    { throttle: 300 },
  )

  return { searched, results }
}