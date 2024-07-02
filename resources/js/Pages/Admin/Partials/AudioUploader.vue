<script setup>
import InputLabel from '@/Shared/InputLabel.vue'
import InputError from '@/Shared/InputError.vue'
import { FwbButton } from 'flowbite-vue'
import { computed } from 'vue'
import OpacityTransition from '@/Shared/Animations/OpacityTransition.vue'

defineProps({ error: String })

const blob = defineModel({ type: Blob })

const src = computed(() => (blob.value ? URL.createObjectURL(blob.value) : null))
</script>

<template>
  <div>
    <InputLabel value="Аудио">
      <input
        ref="input"
        @change="$emit('update:modelValue', $event.target.files[0])"
        accept="audio/*"
        type="file"
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
      />
    </InputLabel>
    <OpacityTransition>
      <div v-show="src" class="flex flex-wrap gap-2.5 mt-4">
        <!--  Play audio file  -->
        <audio :src="src" controls class="grow" />
        <!--  Clear button  -->
        <FwbButton
          @click="
            () => {
              blob = null
              $refs.input.value = null
            }
          "
          type="button"
          pill
          color="alternative"
          size="lg"
          class="px-12 w-full sm:w-auto"
        >
          Очистить
        </FwbButton>
      </div>
    </OpacityTransition>
    <InputError :message="error" />
  </div>
</template>

<style scoped></style>
