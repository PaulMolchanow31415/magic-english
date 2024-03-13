<script setup>
import Opacity300Transition from '@/Animations/Opacity300Transition.vue'

defineProps({
  message: String,
})
</script>

<template>
  <Opacity300Transition>
    <div v-show="message">
      <small class="mt-2 text-sm text-red-600 dark:text-red-500">
        {{ message }}
      </small>
    </div>
  </Opacity300Transition>
</template>
