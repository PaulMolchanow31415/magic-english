placeholder="Search"
<script setup>
import { FwbAvatar, FwbButton, FwbInput } from 'flowbite-vue'
import { Link } from '@inertiajs/vue3'

import { onMounted } from 'vue'
import { initDropdowns } from 'flowbite'

defineProps({
  searchPlaceholder: {
    type: String,
    default: 'Найти',
  },
  searchedValue: {
    type: String,
    required: true,
  },
  addable: {
    type: Boolean,
    default: false,
  },
})

onMounted(() => {
  initDropdowns()
})

defineEmits(['update:searchedValue', 'add'])
</script>

<template>
  <div class="w-full">
    <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
      <div
        class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4"
      >
        <div class="w-full md:w-1/2">
          <form class="flex items-center">
            <FwbInput
              size="sm"
              class="w-full"
              :model-value="searchedValue"
              @input="$emit('update:searchedValue', $event.target.value)"
              :placeholder="searchPlaceholder"
            >
              <template #prefix>
                <Icon
                  class="text-gray-700 dark:text-gray-300"
                  :icon="['fas', 'magnifying-glass']"
                />
              </template>
            </FwbInput>
          </form>
        </div>
        <div
          class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3"
        >
          <FwbButton v-if="addable" @click="$emit('add')" class="justify-center">
            <template #prefix>
              <Icon :icon="['fas', 'plus']" size="sm" />
            </template>
            Добавить
          </FwbButton>

          <template v-if="$slots.filters">
            <button
              id="dropdown-button"
              data-dropdown-toggle="dropdown-body"
              class="flex items-center justify-center gap-2 w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              type="button"
            >
              Фильтры
              <Icon :icon="['fas', 'filter']" size="sm" class="text-gray-600 dark:text-gray-400" />
            </button>
            <div
              id="dropdown-body"
              class="z-10 hidden w-48 bg-white rounded-lg shadow dark:bg-gray-700"
            >
              <ul
                aria-labelledby="dropdown-button"
                class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200"
              >
                <slot name="filters" />
              </ul>
            </div>
          </template>
          <Link :href="route('profile.show')">
            <FwbAvatar
              bordered
              :alt="$page.props.auth.user.name"
              :img="$page.props.auth.user.profile_photo_url"
              size="sm"
            />
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>