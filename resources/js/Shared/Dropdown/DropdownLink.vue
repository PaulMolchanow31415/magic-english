<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

type TagLink = 'button' | 'a'

defineProps<{
  href?: string
  as?: TagLink
}>()

const baseClasses =
  'block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out'
</script>

<template>
  <div>
    <button
      v-if="as === 'button'"
      type="submit"
      class="w-full text-start dropdown-line"
      :class="baseClasses"
    >
      <slot />
    </button>

    <a v-else-if="as === 'a'" :href="href" :class="baseClasses" class="dropdown-line">
      <slot />
    </a>

    <Link v-else :href="href" :class="baseClasses" class="dropdown-line">
      <slot />
    </Link>
  </div>
</template>

<style lang="postcss" scoped>
.dropdown-line {
  @apply flex gap-2.5 items-center;
}
</style>
