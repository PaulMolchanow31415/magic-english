<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({ singers: Array })
</script>

<template>
  <Head title="Исполнители" />

  <section class="py-10 lg:py-32 sm:text-center container mx-auto pt-6 pb-12">
    <h3 class="heading-1 mb-6 sm:mb-12">Исполнители</h3>

    <div class="columns-3 sm:columns-4 gap-4 lg:gap-8">
      <Link
        v-for="singer in singers"
        v-wave="{
          color: 'white',
          duration: 0.2,
        }"
        :key="singer.id"
        :href="route('singer.show', singer.id)"
        class="relative group cursor-pointer mb-6 block active:opacity-80 transition"
      >
        <img
          :src="singer.poster_url"
          :alt="singer.name"
          class="rounded-lg group-hover:backdrop-blur-xl transition"
        />

        <span
          class="flex justify-center items-center w-10 h-10 md:w-16 md:h-16 bg-opacity-70 bg-gray-950 hover:bg-opacity-80 absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 group-hover:opacity-100 opacity-0 rounded-full transition duration-75 text-gray-50"
          type="button"
        >
          <Icon :icon="['fas', 'play']" class="w-5 h-5 md:w-7 md:h-7" />
        </span>
      </Link>
    </div>
  </section>
</template>

<style scoped></style>
