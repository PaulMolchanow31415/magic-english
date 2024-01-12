<script setup>
import BaseHeader from '@/Shared/BaseHeader.vue'
import BaseFooter from '@/Shared/BaseFooter.vue'
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
