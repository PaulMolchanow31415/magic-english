<script setup>
import { FwbInput } from 'flowbite-vue'
import { Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { get, onClickOutside, set, watchThrottled } from '@vueuse/core'
import { useScrollLock } from '@/Composables/useScrollLock.ts'
import DropdownTransition from '@/Animations/ComboboxTransition.vue'

const searched = ref('')
const results = ref([])
const isFocused = ref(false)
const comboBox = ref(null)
const searchInput = ref(null)

const isComboboxVisible = computed(() => get(isFocused) && get(searched) && get(results).length > 0)

function clear() {
  set(results, [])
  set(searched, '')
  set(isFocused, false)
}

onClickOutside(comboBox, () => set(isFocused, false), { ignore: [searchInput] })

watchThrottled(
  searched,
  (query) =>
    query &&
    axios
    .get(route('api.global-search'), { params: { query } })
    .then((res) => set(results, res.data))
    .catch(() => set(results, [])),
  { throttle: 900 },
)

useScrollLock(isComboboxVisible)
</script>

<template>
  <form role="search" autocomplete="off">
    <FwbInput
      ref="searchInput"
      v-model.lazy="searched"
      @focus="isFocused = true"
      type="search"
      placeholder="Поиск"
      class="text-gray-700 dark:text-gray-300"
      size="sm"
      autocomplete="off"
    >
      <template #prefix>
        <Icon class="text-gray-700 dark:text-gray-300" :icon="['fas', 'magnifying-glass']" />
      </template>
    </FwbInput>

    <DropdownTransition>
      <div v-show="isComboboxVisible" ref="comboBox" role="combobox" class="relative">
        <div class="z-50 pt-1.5 absolute w-full">
          <aside class="relative bg-white rounded-lg shadow w-full dark:bg-gray-700">
            <ul
              class="max-h-[75vh] space-y-2 p-4 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
              role="listbox"
            >
              <li v-for="result in results">
                <Link
                  v-text="result.name"
                  :href="result.url"
                  @click="clear"
                  class="block text-slate-700 hover:text-violet-700 dark:hover:text-violet-400 dark:text-slate-200 rounded-lg p-4 ring-1 ring-slate-100 dark:ring-slate-500 hover:ring-violet-700 dark:hover:ring-violet-400 select-none cursor-pointer"
                />
              </li>
            </ul>
            <div
              class="absolute bottom-0 left-0 right-0 bg-white dark:bg-gray-700 h-3 rounded-b-lg"
            />
          </aside>
        </div>
      </div>
    </DropdownTransition>
  </form>
</template>

<style scoped></style>
