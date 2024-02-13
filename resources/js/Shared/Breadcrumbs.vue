<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const breadcrumbs = computed(() => usePage().props.breadcrumbs || [])
</script>

<template>
  <nav
    v-if="breadcrumbs.length > 0"
    class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Breadcrumbs"
  >
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <li
        v-for="(crumb, index) in breadcrumbs"
        :key="index"
        :aria-current="crumb.current ? 'page' : null"
        class="inline-flex items-center"
      >
        <Link
          :href="crumb.url"
          class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
        >
          <template v-if="!index">
            <Icon :icon="['fas', 'house']" class="w-3 me-2.5" />
            {{ crumb.title }}
          </template>
          <template v-else>
            <Icon :icon="['fas', 'angle-right']" class="rtl:rotate-180 block w-3 text-gray-400" />
            <span
              :class="{ 'text-gray-500 dark:text-gray-400': breadcrumbs.length === index + 1 }"
              class="ms-1 md:ms-2"
            >
              {{ crumb.title }}
            </span>
          </template>
        </Link>
      </li>
    </ol>
  </nav>
</template>

<style scoped></style>