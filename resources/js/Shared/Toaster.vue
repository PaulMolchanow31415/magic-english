<script setup>
import { FwbToast } from 'flowbite-vue'
import Toast from '@/Types/Toast.js'
import CloseButton from '@/Shared/CloseButton.vue'
import SlideLeftTransition from '@/Animations/SlideLeftTransition.vue'

defineProps({
  tosts: {
    type: Array,
    required: true,
    default: [],
    validator(values = []) {
      return values.every((t) => t instanceof Toast)
    },
  },
  closable: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['close'])
</script>

<template>
  <teleport to="body">
    <div class="fixed right-5 top-5 flex flex-col gap-2.5">
      <SlideLeftTransition>
        <FwbToast
          v-for="(message, index) in tosts"
          :type="message.type"
          v-show="message.isShow"
          :key="index"
        >
          <template #icon>
            <Icon v-if="message.type === 'success'" :icon="['fas', 'check']" />
            <Icon v-else-if="message.type === 'info'" :icon="['fas', 'circle-info']" />
            <Icon v-else-if="message.type === 'warning'" :icon="['fas', 'bolt']" size="xs" />
          </template>
          <div class="info-line">
            {{ message.value }}
            <CloseButton v-if="closable" @close="$emit('close', index)" />
          </div>
        </FwbToast>
      </SlideLeftTransition>
    </div>
  </teleport>
</template>

<style scoped></style>
