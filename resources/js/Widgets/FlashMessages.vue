<script setup>
import Toaster from '@/Shared/Toaster.vue'
import { useFlashMessages } from '@/Composables'

const { flashMessages, closeMessage } = useFlashMessages()
</script>

<template>
  <Teleport to="body">
    <Toaster :toasts="flashMessages" @close="closeMessage" />
  </Teleport>
</template>

<style scoped></style>
