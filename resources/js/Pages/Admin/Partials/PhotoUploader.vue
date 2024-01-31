<script setup>
import { FwbFileInput, FwbInput } from 'flowbite-vue'
import SecondaryButton from '@/Shared/SecondaryButton.vue'
import InputLabel from '@/Shared/InputLabel.vue'

defineProps({
  modelValue: {
    type: [null, File],
    required: true,
  },
  externalPath: {
    type: [null, String],
    required: true,
    default: '',
  },
  hasPhoto: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['update:modelValue', 'update:externalPath', 'deletePhoto'])
</script>

<template>
  <div>
    <FwbFileInput
      :model-value="modelValue"
      @update:model-value="$emit('update:modelValue', $event)"
      dropzone
    />
    <div class="flex flex-wrap gap-4 justify-stretch items-end mt-4">
      <InputLabel value="Или введите ссылку на изображение" class="flex-grow">
        <FwbInput
          :model-value="externalPath"
          @input="$emit('update:externalPath', $event.target.value)"
          name="url"
          size="sm"
          placeholder="https://example.image.png"
        />
      </InputLabel>
      <SecondaryButton
        class="min-h-9"
        v-if="hasPhoto"
        type="button"
        @click.prevent="$emit('deletePhoto')"
      >
        Удалить фотографию
      </SecondaryButton>
    </div>
  </div>
</template>

<style scoped></style>