<script setup lang="ts">
import { FwbButton } from 'flowbite-vue'
// prettier-ignore
withDefaults(defineProps<{
  type?: 'submit' | 'button',
  loading?: boolean
}>(), {
  type: 'submit',
  loading: false,
})
</script>

<template>
  <FwbButton
    gradient="blue"
    :type="type"
    class="flex flex-nowrap font-medium"
    :loading="loading"
    :class="{ 'opacity-65': loading }"
    :disabled="loading"
  >
    <slot>Сохранить</slot>
  </FwbButton>
</template>
