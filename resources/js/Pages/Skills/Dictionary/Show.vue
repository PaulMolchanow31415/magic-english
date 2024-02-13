<script setup>
import { FwbAvatar, FwbButton, FwbHeading, FwbP } from 'flowbite-vue'
import DiscussionSection from '@/Shared/DiscussionSection.vue'
import AutoHead from '@/Shared/AutoHead.vue'
import { router } from '@inertiajs/vue3'
import VocabularyList from '@/Pages/Skills/Patials/VocabularyList.vue'

defineProps({ dictionary: Object })

function add(dictionary) {
  router.post(route('student.add-dictionary', { id: dictionary.id }), null, {
    preserveScroll: true,
    preserveState: true,
  })
}
</script>

<template>
  <AutoHead />

  <article>
    <section
      @click.stop="add(dictionary)"
      class="flex items-center gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition duration-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
    >
      <FwbAvatar size="xl" :img="dictionary.poster_url" class="poster" />
      <div class="grow">
        <FwbHeading tag="h3" v-text="dictionary.category" />
        <FwbP>{{ dictionary.vocabularies.length }} слов</FwbP>
      </div>
      <FwbButton size="lg" gradient="green" class="font-bold px-12 me-4"> Изучить набор </FwbButton>
    </section>

    <VocabularyList :vocabularies="dictionary.vocabularies" />

    <DiscussionSection :discussionable-id="dictionary.id" />
  </article>
</template>

<style scoped></style>