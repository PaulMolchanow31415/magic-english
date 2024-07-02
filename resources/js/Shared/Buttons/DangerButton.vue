<script setup lang="ts">
import { FwbButton } from 'flowbite-vue'

withDefaults(defineProps<{ disabled?: boolean }>(), { disabled: false })
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
