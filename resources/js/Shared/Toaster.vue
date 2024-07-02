<script setup lang="ts">
import { FwbToast } from 'flowbite-vue'
import CloseButton from './CloseButton.vue'
import SlideLeftTransition from './Animations/SlideLeftTransition.vue'
import { Toast } from '../Entities'

type FwbToastType = typeof FwbToast.__defaults.type

withDefaults(defineProps<{ toasts: Toast[] }>(), { toasts: () => [] })

defineEmits<{ close: [index: number] }>()
</script>

<template>
  <div class="fixed right-5 top-5 flex flex-col gap-2.5">
    <SlideLeftTransition>
      <FwbToast
        v-for="(message, index) in toasts"
        :type="message.type as FwbToastType"
        :key="index"
      >
        <template #icon>
          <Icon v-if="message.type === 'success'" :icon="['fas', 'check']" />
          <Icon v-else-if="message.type === 'info'" :icon="['fas', 'circle-info']" />
          <Icon v-else-if="message.type === 'warning'" :icon="['fas', 'bolt']" size="xs" />
        </template>
        <div class="flex items-center gap-2">
          {{ message.value }}
          <CloseButton v-if="message.closable" @close="$emit('close', index)" />
        </div>
      </FwbToast>
    </SlideLeftTransition>
  </div>
</template>

<style scoped></style>
