<script>
import InputLabel from '@/Shared/InputLabel.vue'
import InputError from '@/Shared/InputError.vue'
import { FwbButton, FwbInput } from 'flowbite-vue'
import { defineComponent } from 'vue'

export default defineComponent({
  components: { FwbButton, FwbInput, InputError, InputLabel },

  props: {
    modelValue: Blob,
    errorMessage: String,
  },

  emits: ['update:modelValue'],

  methods: {
    onReset() {
      this.$refs.input.value = null
      this.$emit('update:modelValue', null)
    },
  },

  computed: {
    src() {
      return this.modelValue ? URL.createObjectURL(this.modelValue) : null
    },
  },
})
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
    <div v-show="src" class="flex flex-wrap gap-2.5 mt-4">
      <!--  Play audio file  -->
      <audio :src="src" controls class="grow" />
      <!--  Clear button  -->
      <FwbButton
        @click="onReset"
        type="button"
        pill
        color="alternative"
        size="lg"
        class="px-12 w-full sm:w-auto"
      >
        Очистить
      </FwbButton>
    </div>
    <InputError :message="errorMessage" />
  </div>
</template>

<style scoped></style>
