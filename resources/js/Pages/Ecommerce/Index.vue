<script setup>
import ExtraLessonsSection from '@/Pages/Ecommerce/Partials/ExtraLessonsSection.vue'
import { Link, Head } from '@inertiajs/vue3'

defineProps({
  filters: Object,
  lessons: Array,
  hasCartProducts: Boolean,
})
</script>

<template>
  <Head title="Тарифы" />

  <div class="container mx-auto pt-32 pb-24">
    <!-- <PlansSection />-->

    <!-- class="pt-48" -->
    <ExtraLessonsSection :lessons="lessons" />
  </div>

  <!-- Open cart button -->
  <Link
    v-if="hasCartProducts"
    :href="route('cart.show')"
    class="z-50 fixed bottom-12 left-12 rounded-full px-5 py-4 bg-blue-600 text-white animate-ping hover:animate-none focus:ring-8 ring-blue-400"
  >
    <span class="sr-only">Окрыть корзину</span>
    <Icon :icon="['fas', 'shopping-cart']" size="md" />
  </Link>
</template>

<style scoped></style>