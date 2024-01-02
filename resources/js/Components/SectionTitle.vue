<template>
  <div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
      <FwbHeading tag="h6" class="font-medium">
        <slot name="title" />
      </FwbHeading>

      <FwbP class="text-sm mt-1" color="text-gray-600 dark:text-gray-400">
        <slot name="description" />
      </FwbP>
    </div>

    <div class="px-4 sm:px-0">
      <slot name="aside" />
    </div>
  </div>
</template>
<script setup>
import { FwbHeading, FwbP } from 'flowbite-vue'
</script>
