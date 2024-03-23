<script setup>
import { Head } from '@inertiajs/vue3'
import { FwbButton, FwbCard, FwbHeading, FwbModal } from 'flowbite-vue'
import { ref } from 'vue'
import Translatable from '@/Shared/Translatable.vue'
import Opacity300Transition from '@/Animations/Opacity300Transition.vue'

defineProps({ lessons: Array })

const selected = ref()
</script>

<template>
  <Head title="Купленные уроки" />

  <div class="container mx-auto pt-10 lg:pt-32 pb-8 lg:pb-24">
    <h3 class="heading-1 mb-8 md:mb-12">Купленные уроки</h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-x-4 gap-y-6">
      <FwbCard
        v-for="lesson in lessons"
        :img-src="lesson.poster_url"
        :img-alt="lesson.name"
        variant="horizontal"
        @click="selected = lesson"
        class="cursor-pointer"
      >
        <p class="dark:text-white mx-4 my-6 font-medium">{{ lesson.name }}</p>
      </FwbCard>
    </div>
  </div>

  <Opacity300Transition>
    <FwbModal v-if="selected" size="5xl" @close="selected = null">
      <template #header>
        <FwbHeading tag="h6" class="flex items-center text-lg">{{ selected.name }}</FwbHeading>
      </template>
      <template #body>
        <Translatable>
          <article v-html="selected?.content" class="no-tailwindcss" />
        </Translatable>
      </template>
      <template #footer>
        <FwbButton @click="selected = null" color="alternative" size="lg">Закрыть</FwbButton>
      </template>
    </FwbModal>
  </Opacity300Transition>
</template>

<style scoped></style>
