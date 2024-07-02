<script setup>
import { useDark, useToggle } from '@vueuse/core'

const isDark = useDark()
const toggleDark = useToggle(isDark)
</script>

<template>
  <button
    type="button"
    @click="toggleDark()"
    class="w-[2.625rem] h-[2.625rem] flex justify-center items-center bg-gray-50 text-gray-700 dark:border-gray-700 dark:text-gray-200 dark:bg-gray-800 rounded hover:text-blue-600 dark:hover:text-white border border-gray-300 gap-2.5"
    aria-label="Сменить тему"
  >
    <span class="sr-only">Сменить тему</span>
    <Icon v-if="isDark" :icon="['fas', 'sun']" />
    <Icon v-else :icon="['fas', 'moon']" />
    <slot />
  </button>
</template>
