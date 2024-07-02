<script setup lang="ts">
import { FwbButton } from 'flowbite-vue'

withDefaults(defineProps<{ disabled?: boolean }>(), { disabled: false })
</script>

<template>
  <FwbButton
    :disabled="disabled"
    color="alternative"
    size="sm"
    class="font-normal"
    :class="{ 'opacity-65': disabled }"
  >
    <slot />
  </FwbButton>
</template>
