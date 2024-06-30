import { useWindowSize } from '@vueuse/core'
import { computed } from 'vue'

export function useDeviceSize(breakpoints?: { mobile?: number; tablet?: number }) {
  const { width } = useWindowSize()

  const isMobile = computed(() => width.value <= (breakpoints?.mobile ?? 460))

  return {
    isMobile,
    isGreaterThen: (breakpoint: number) => computed(() => width.value > breakpoint),
  }
}
