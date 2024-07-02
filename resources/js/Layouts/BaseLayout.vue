<script setup>
import BaseHeader from '@/Widgets/BaseHeader.vue'
import BaseFooter from '@/Widgets/BaseFooter.vue'
import CookiePopup from '@/Widgets/CookiePopup.vue'
import AudioPlayer from '@/Widgets/AudioPlayer.vue'
import FlashMessages from '@/Widgets/FlashMessages.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col h-auto bg-white dark:bg-gray-900">
    <BaseHeader />

    <FlashMessages />

    <!-- Page Content -->
    <div class="flex-grow relative px-4">
      <slot />
    </div>

    <CookiePopup />

    <AudioPlayer />

    <BaseFooter />
  </div>
</template>
