<script setup>
import Breadcrumbs from '@/Widgets/Breadcrumbs.vue'
</script>

<template>
  <div class="container mx-auto pt-6 pb-12">
    <Breadcrumbs />

    <div class="pt-8">
      <slot />
    </div>
  </div>
</template>
