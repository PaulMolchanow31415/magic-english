<script setup>
import BaseHeader from '@/Shared/BaseHeader.vue'
import BaseFooter from '@/Shared/BaseFooter.vue'
import { provide } from 'vue'

provide('avatarInitials', (name = 'default') => {
  name = name.trim().split(' ')
  return (
    name.length > 1 ? name[0].charAt(0).concat(name[1].charAt(0)) : name[0].charAt(0)
  ).toUpperCase()
})
</script>

<template>
  <div class="min-h-screen flex flex-col h-auto bg-white dark:bg-gray-900">
    <BaseHeader />

    <!-- Page Content -->
    <main class="flex-grow">
      <slot />
    </main>

    <BaseFooter />
  </div>
</template>
