<script setup>
import { FwbButton, FwbModal } from 'flowbite-vue'
import Opacity300Transition from '@/Animations/Opacity300Transition.vue'

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['confirm', 'close'])
</script>

<template>
  <form @submit.prevent="$emit('confirm')">
    <Opacity300Transition>
      <FwbModal
        size="md"
        v-show="show"
        @close="$emit('close')"
        @click:outside="$emit('close')"
        :persistent="false"
      >
        <template #body>
          <div class="text-center">
            <Icon
              :icon="['fas', 'trash-can']"
              size="3x"
              class="mb-4 text-gray-500 dark:text-gray-400"
            />
            <p class="dark:text-gray-200 text-sm px-4">
              <slot name="message" />
            </p>
          </div>
        </template>
        <template #footer>
          <div class="flex justify-center gap-4">
            <FwbButton @click="$emit('close')" type="button" size="lg" color="alternative">
              Отмена
            </FwbButton>
            <FwbButton type="submit" size="lg" color="red"> Уверен </FwbButton>
          </div>
        </template>
      </FwbModal>
    </Opacity300Transition>
  </form>
</template>

<style scoped></style>
