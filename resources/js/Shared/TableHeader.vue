<script setup>
import { FwbAvatar, FwbButton } from 'flowbite-vue'
import { Link } from '@inertiajs/vue3'

import { onMounted } from 'vue'
import { initDropdowns } from 'flowbite'
import { useEventListener } from '@vueuse/core'
import Tooltip from '@/Shared/Tooltip.vue'
import SearchInput from '@/Shared/Admin/SearchInput.vue'

const props = defineProps({
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

const emit = defineEmits(['update:searchedValue', 'add'])

function onKeyPress(event) {
  if (event.shiftKey && event.code === 'KeyN' && props.addable) {
    event.preventDefault()
    emit('add')
  }
}

useEventListener(document, 'keypress', onKeyPress)
</script>

<template>
  <div class="w-full">
    <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
      <div
        class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4"
      >
        <div class="w-full md:w-1/2">
          <form class="flex items-center">
            <SearchInput
              :model-value="searchedValue"
              @input="$emit('update:searchedValue', $event)"
              :placeholder="searchPlaceholder"
            />
          </form>
        </div>
        <div
          class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3"
        >
          <Tooltip>
            <template #trigger>
              <FwbButton v-if="addable" @click="$emit('add')" class="justify-center">
                <template #prefix>
                  <Icon :icon="['fas', 'plus']" size="sm" />
                </template>
                Добавить
              </FwbButton>
            </template>
            <template #content>
              <p class="leading-loose"><kbd>Shift</kbd>&nbsp;+&nbsp;<kbd>N</kbd></p>
            </template>
          </Tooltip>

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

<!--
/*  .filters-dropdown-label {
      @apply flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600;
    }
    .filters-dropdown-checkbox {
      @apply w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600
      dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500;
    }
    .filters-dropdown-text {
      @apply w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300 select-none;
    }*/
<template #filters>
        <li>
          <label class="filters-dropdown-label">
            <input
              v-model="selectedFilters.is_banned"
              type="checkbox"
              class="filters-dropdown-checkbox"
            />
            <span class="filters-dropdown-text">Заблокированный</span>
          </label>
        </li>
        <li>
          <label class="filters-dropdown-label">
            <input
              v-model="selectedFilters.is_admin"
              type="checkbox"
              class="filters-dropdown-checkbox"
            />
            <span class="filters-dropdown-text">Администратор</span>
          </label>
        </li>
      </template>-->