<script setup>
import { FwbButton } from 'flowbite-vue'

defineProps({
  disabled: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <FwbButton
    type="button"
    class="font-medium"
    :class="{ 'opacity-65': disabled }"
    :disabled="disabled"
    gradient="red"
  >
    <slot />
  </FwbButton>
</template>
