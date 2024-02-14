import { router, usePage } from '@inertiajs/vue3'
import { isRef, toRef, warn } from 'vue'
import { useInfiniteScroll } from '@vueuse/core'

export default function (element, wrappedList) {
  if (!isRef(wrappedList)) {
    warn('2 параметр должен быть ref, toRef(значение из props)', wrappedList)
    return
  }

  const page = usePage()
  const allItems = toRef(wrappedList.value.data)
  const initialUrl = page.url

  const settings = {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  }

  const scrollConfig = (direction = 'bottom') => ({
    interval: 1000,
    distance: 10,
    direction,
  })

  const replaceUrl = () => history.replaceState({}, page.title, initialUrl)

  useInfiniteScroll(
    element,
    () => {
      router.visit(wrappedList.value.next_page_url, {
        onSuccess() {
          allItems.value.push(...wrappedList.value.data)
          replaceUrl()
        },
        ...settings,
      })
    },
    {
      canLoadMore: () => Boolean(wrappedList.value.next_page_url),
      ...scrollConfig(),
    },
  )

  useInfiniteScroll(
    element,
    () => {
      router.visit(wrappedList.value.prev_page_url, {
        onSuccess() {
          allItems.value.unshift(...wrappedList.value.data)
          replaceUrl()
        },
        ...settings,
      })
    },
    {
      canLoadMore: () => Boolean(wrappedList.value.prev_page_url),
      ...scrollConfig('top'),
    },
  )

  return { allItems }
}