<script setup>
import { Head } from '@inertiajs/vue3'
import { FwbButton, FwbCard, FwbHeading, FwbModal, FwbP } from 'flowbite-vue'
import { ref } from 'vue'

defineProps({ lessons: Array })

const selected = ref()
</script>

<template>
  <Head title="Купленные уроки" />

  <div class="container mx-auto pt-32 pb-24">
    <FwbHeading tag="h1" class="mb-12">Купленные уроки</FwbHeading>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-x-4 gap-y-6">
      <FwbCard
        v-for="lesson in lessons"
        :img-src="lesson.poster_url"
        :img-alt="lesson.name"
        variant="horizontal"
        @click="selected = lesson"
        class="cursor-pointer"
      >
        <FwbP class="mx-4 my-6 font-medium">{{ lesson.name }}</FwbP>
      </FwbCard>
    </div>
  </div>

  <FwbModal v-if="selected" size="5xl" @close="selected = null">
    <template #header>
      <FwbHeading tag="h6" class="flex items-center text-lg">{{ selected.name }}</FwbHeading>
    </template>
    <template #body>
      <article v-html="selected?.content" class="no-tailwindcss" />
    </template>
    <template #footer>
      <div class="flex justify-between">
        <FwbButton @click="selected = null" color="alternative" size="lg">Закрыть </FwbButton>
      </div>
    </template>
  </FwbModal>
</template>

<style scoped></style>