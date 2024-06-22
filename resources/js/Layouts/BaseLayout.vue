<script setup>
import BaseHeader from '@/Widgets/BaseHeader.vue'
import BaseFooter from '@/Widgets/BaseFooter.vue'
import { provide } from 'vue'
import CookiePopup from '@/Widgets/CookiePopup.vue'
import AudioPlayer from '@/Widgets/AudioPlayer.vue'
import avatarInitials from '@/Helpers/avatarInitials.js'
import randomInt from '@/Helpers/randomInt.js'
import shuffle from '@/Helpers/shuffle.js'

provide('avatarInitials', avatarInitials)

provide('randomInt', randomInt)

provide('shuffle', shuffle)
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
