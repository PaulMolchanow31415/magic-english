<script setup lang="ts">
import { SuggestListItem } from '../Entities'
import { FwbInput } from 'flowbite-vue'
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import InputLabel from '../Shared/InputLabel.vue'
import ComboboxTransition from '../Shared/Animations/ComboboxTransition.vue'

withDefaults(
  defineProps<{
    results?: SuggestListItem[]
    label?: string
    placeholder?: string
  }>(),
  {
    results: () => [],
    placeholder: 'Найти...',
  },
)

defineEmits(['add'])

const model = defineModel({ type: String, required: true })

const isFocused = ref(false)
const comboBox = ref(null)
const searchInput = ref(null)

onClickOutside(comboBox, () => (isFocused.value = false), { ignore: [searchInput] })
</script>

<template>
  <div role="searchbox">
    <div>
      <InputLabel :value="label">
        <FwbInput
          ref="searchInput"
          v-model="model"
          @focus="isFocused = true"
          size="sm"
          :placeholder="placeholder"
          class="w-full"
        >
          <template #prefix>
            <Icon
              class="text-gray-700 dark:text-gray-300"
              :icon="['fas', 'magnifying-glass']"
              size="sm"
            />
          </template>
        </FwbInput>
      </InputLabel>
    </div>

    <ComboboxTransition>
      <div v-show="isFocused && results.length > 0" ref="comboBox" class="relative">
        <div class="z-50 pt-1.5 absolute w-full">
          <aside class="relative bg-white rounded-lg shadow w-full dark:bg-gray-700">
            <ul
              class="max-h-52 p-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
              role="listbox"
            >
              <li v-for="result in results" :key="result.id">
                <div
                  class="group flex items-center ps-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <button
                    @click="$emit('add', result)"
                    type="button"
                    class="opacity-0 group-hover:opacity-100 transition duration-75 flex justify-center items-center p-0.5 w-4 text-blue-600 dark:text-blue-400 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                  >
                    <Icon :icon="['fas', 'plus']" class="text-xs" />
                  </button>
                  <span
                    class="w-full py-2 ms-2 text-xs font-medium text-gray-900 rounded dark:text-gray-300"
                    v-text="result.value"
                  />
                </div>
              </li>
            </ul>
            <div
              class="absolute bottom-0 left-0 right-0 bg-white dark:bg-gray-700 h-3 rounded-b-lg"
            />
          </aside>
        </div>
      </div>
    </ComboboxTransition>
  </div>
</template>

<style scoped></style>
