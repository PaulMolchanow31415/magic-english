<script setup>
import { FwbToast } from 'flowbite-vue'
import ToastWrapper from '@/Shared/ToastWrapper.vue'
import Toast from '@/Classes/Toast.js'
import ToastCloseButton from '@/Shared/ToastCloseButton.vue'

defineProps({
  tosts: {
    type: Array,
    required: true,
    default: [],
    validator(values = []) {
      return values.length === values.filter((m) => m instanceof Toast).length
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
    <ToastWrapper>
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
          <ToastCloseButton v-if="closable" @close="$emit('close', index)" />
        </div>
      </FwbToast>
    </ToastWrapper>
  </teleport>
</template>

<style scoped></style>