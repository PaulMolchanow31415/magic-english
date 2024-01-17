<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/vue3'
import { FwbDropdown } from 'flowbite-vue'

export default defineComponent({
  name: 'CommentActionDropdown',
  components: { FwbDropdown, Link },
  props: {
    canDelete: Boolean,
    canReport: Boolean,
  },
  emits: ['delete', 'report'],
})
</script>

<template>
  <FwbDropdown placement="left">
    <template #trigger>
      <button
        class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        type="button"
      >
        <Icon :icon="['fas', 'ellipsis']" size="lg" />
        <span class="sr-only">Настройки комментария</span>
      </button>
    </template>
    <template #default>
      <!-- Dropdown menu -->
      <ul class="text-sm text-gray-700 dark:text-gray-200">
        <li v-if="canDelete">
          <button
            class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
            type="button"
            @click="$emit('delete')"
          >
            Удалить
          </button>
        </li>
        <li v-if="canReport">
          <button
            class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
            type="button"
            @click="$emit('report')"
          >
            Пожаловаться
          </button>
        </li>
      </ul>
    </template>
  </FwbDropdown>
</template>

<style scoped></style>