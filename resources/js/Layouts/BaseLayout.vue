<script setup>
import BaseHeader from '@/Shared/BaseHeader.vue'
import BaseFooter from '@/Shared/BaseFooter.vue'
import { provide } from 'vue'
import CookiePopup from '@/Shared/CookiePopup.vue'
import AudioPlayer from '@/Shared/AudioPlayer.vue'

provide('avatarInitials', (name = 'default') => {
  name = name.trim().split(' ')
  return (
    name.length > 1 ? name[0].charAt(0).concat(name[1].charAt(0)) : name[0].charAt(0)
  ).toUpperCase()
})

provide('randomInt', (min, max) => {
  const minCeiled = Math.ceil(min)
  const maxFloored = Math.floor(max)
  return Math.floor(Math.random() * (maxFloored - minCeiled + 1) + minCeiled)
})

provide('shuffle', (array = []) => {
  return array.sort(() => Math.random() - 0.5)
})
</script>

<template>
  <div class="min-h-screen flex flex-col h-auto bg-white dark:bg-gray-900">
    <BaseHeader />

    <!-- Page Content -->
    <div class="flex-grow relative px-4">
      <slot />
    </div>

    <CookiePopup />

    <AudioPlayer />

    <BaseFooter />
  </div>
</template>
