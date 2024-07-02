<script setup>
import { FwbFileInput, FwbInput } from 'flowbite-vue'
import SecondaryButton from '@/Shared/Buttons/SecondaryButton.vue'
import InputLabel from '@/Shared/InputLabel.vue'

defineProps({
  hasPhoto: {
    type: Boolean,
    default: false,
  },
})

const file = defineModel({
  type: [null, File],
  required: true,
})

const externalPath = defineModel('externalPath', {
  type: [null, String],
  required: true,
  default: '',
})

defineEmits(['deletePhoto'])
</script>

<template>
  <div>
    <FwbFileInput v-model="file" dropzone />
    <div class="flex flex-wrap gap-4 justify-stretch items-end mt-4">
      <InputLabel value="Или введите ссылку на изображение" class="flex-grow">
        <FwbInput
          v-model="externalPath"
          name="poster-url"
          type="url"
          size="sm"
          placeholder="https://example.image.png"
        />
      </InputLabel>
      <SecondaryButton class="min-h-9" v-if="hasPhoto" @click.prevent="$emit('deletePhoto')">
        Удалить фотографию
      </SecondaryButton>
    </div>
  </div>
</template>

<style scoped></style>
