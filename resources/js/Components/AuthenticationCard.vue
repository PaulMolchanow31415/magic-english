<template>
  <div
    class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:from-gray-950 dark:to-gray-900 dark:bg-gradient-to-br dark:from-20%"
  >
    <div>
      <slot name="logo" />
    </div>

    <div
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden sm:rounded-lg"
    >
      <h2 v-if="$slots.heading" class="mb-8 text-2xl font-bold text-gray-900 dark:text-white">
        <slot name="heading" />
      </h2>

      <slot />
    </div>
  </div>
</template>
<script setup></script>
