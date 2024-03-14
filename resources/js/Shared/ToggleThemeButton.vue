<script setup>
import { useDark, useToggle } from '@vueuse/core/index'

const isDark = useDark()
const toggleDark = useToggle(isDark)
</script>

<template>
  <button type="button" @click="toggleDark()" class="control-btn" aria-label="Сменить тему">
    <span class="sr-only">Сменить тему</span>
    <Icon v-if="isDark" :icon="['fas', 'sun']" />
    <Icon v-else :icon="['fas', 'moon']" />
    <slot />
  </button>
</template>
