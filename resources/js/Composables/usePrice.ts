import { ref, watch } from 'vue'

export function usePrice(price: number, min?: number, max?: number) {
  const highest = ref(max || price > 1000 ? price : 1000)
  const lowest = ref(min || 10)
  const watchable = ref(price)

  watch(watchable, (updated) => {
    if (updated < lowest.value) {
      watchable.value = lowest.value
    }
    if (highest.value < lowest.value) {
      highest.value = lowest.value
    } else if (highest.value < updated) {
      highest.value = updated
    }
  })

  return {
    price: watchable,
    min: lowest,
    max: highest,

    clear() {
      highest.value = 1000
      watchable.value = lowest.value
    },
  }
}
