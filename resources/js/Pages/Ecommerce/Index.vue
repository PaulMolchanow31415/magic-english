<script setup>
import ExtraLessonsSection from '@/Pages/Ecommerce/Partials/ExtraLessonsSection.vue'
import { Head, Link } from '@inertiajs/vue3'
import { useWindowSize } from '@vueuse/core'
import { computed } from 'vue'

defineProps({
  filters: Object,
  lessons: Array,
  hasCartProducts: Boolean,
})

const { width } = useWindowSize()

const isMobile = computed(() => width.value <= 400)
</script>

<template>
  <Head title="Тарифы" />

  <div class="container mx-auto pt-10 lg:pt-32 pb-14 lg:pb-24">
    <ExtraLessonsSection :lessons="lessons" />

    <!--
    coming soon...
    <PlansSection class="pt-48" />
    -->
  </div>

  <!-- Open cart button -->
  <Link
    v-if="hasCartProducts"
    :href="route('cart.show')"
    class="z-50 fixed bottom-12 left-12 rounded-full flex justify-center items-center w-16 h-16 bg-blue-600 text-white animate-ping hover:animate-none focus:ring-8 ring-blue-400"
    :class="{ '!h-12 !w-12': isMobile }"
  >
    <span class="sr-only">Окрыть корзину</span>
    <Icon :icon="['fas', 'shopping-cart']" :size="isMobile ? 'sm' : 'lg'" />
  </Link>
</template>

<style scoped></style>