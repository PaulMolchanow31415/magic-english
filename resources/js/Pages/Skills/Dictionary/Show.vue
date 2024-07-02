<script setup>
import { FwbAvatar, FwbButton, FwbHeading, FwbP } from 'flowbite-vue'
import { DiscussionSection } from '@/Widgets/Dicsussion'
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
      class="flex flex-wrap items-center justify-center sm:justify-normal gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition duration-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
    >
      <FwbAvatar size="xl" :img="dictionary.poster_url" class="poster">
        <template #placeholder>
          <Icon :icon="['fas', 'book-bookmark']" size="8x" />
        </template>
      </FwbAvatar>
      <div class="sm:grow text-center sm:text-left">
        <FwbHeading
          tag="h3"
          v-text="dictionary.category"
          class="text-2xl sm:text-3xl line-clamp-1"
        />
        <FwbP>{{ dictionary.vocabularies.length }} слов</FwbP>
      </div>
      <FwbButton size="lg" gradient="green" class="font-bold px-12 me-4 w-full sm:w-auto">
        Изучить набор
      </FwbButton>
    </section>

    <VocabularyList :vocabularies="dictionary.vocabularies" />

    <DiscussionSection :discussion-id="dictionary.discussion.id" />
  </article>
</template>

<style scoped></style>
