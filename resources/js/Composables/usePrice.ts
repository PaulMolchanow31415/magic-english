import { ref, watch } from 'vue'
import { set } from '@vueuse/core'

export default function (price: number, min?: number, max?: number) {
  const highest = ref(max || price > 1000 ? price : 1000)
  const lowest = ref(min || 10)
  const watchable = ref(price)

  watch(watchable, (updated) => {
    if (updated < lowest.value) {
      set(watchable, lowest.value)
    }
    if (highest.value < lowest.value) {
      set(highest, lowest.value)
    } else if (highest.value < updated) {
      set(highest, updated)
    }
  })

  return {
    price: watchable,
    min: lowest,
    max: highest,

    clear() {
      set(highest, 1000)
      set(watchable, lowest.value)
    },
  }
}
