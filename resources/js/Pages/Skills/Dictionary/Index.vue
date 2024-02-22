<script setup>
import { router } from '@inertiajs/vue3'
import { FwbButton, FwbCard, FwbHeading, FwbP } from 'flowbite-vue'
import useInfiniteScrollLoader from '@/Composables/useInfiniteScrollLoader.js'
import { ref, toRef } from 'vue'
import AutoHead from '@/Shared/AutoHead.vue'
import FilterPane from '@/Pages/Skills/Patials/FilterPane.vue'

const props = defineProps({
  dictionaries: Object,
  learnableVocabulariesCount: Number,
  filters: Object,
})

const dictionaries = toRef(props, 'dictionaries')

const list = ref(null)

const { allItems } = useInfiniteScrollLoader(list, dictionaries)
</script>

<template>
  <AutoHead />

  <section
    @click.stop="router.visit(route('student.vocabularies.dashboard'))"
    v-if="learnableVocabulariesCount > 0"
    class="group dashboard-cta-section"
  >
    <Icon
      :icon="['fas', 'book-bookmark']"
      size="3x"
      class="text-gray-700 group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-white"
    />
    <div class="grow">
      <FwbHeading tag="h4" class="group-hover:text-blue-600 dark:group-hover:text-white">
        Изучаемые слова
      </FwbHeading>
      <FwbP class="group-hover:text-blue-600 dark:text-gray-600 dark:group-hover:text-white">
        {{ learnableVocabulariesCount }} слов
      </FwbP>
    </div>
    <FwbButton size="lg" gradient="purple" class="dashboard-cta-button"> Повторить </FwbButton>
  </section>

  <FilterPane :filters="filters" />

  <div
    ref="list"
    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-3 gap-y-4"
  >
    <FwbCard
      @click.stop="router.visit(route('skills.dictionary.show', { category: dictionary.category }))"
      v-for="dictionary in allItems"
      :key="dictionary.id"
      :img-alt="dictionary.category"
      :img-src="dictionary.poster_url"
      class="group card min-h-44"
      :title="dictionary.category"
    >
      <div class="p-3">
        <h5
          class="text-lg leading-tight text-center font-semibold tracking-tight text-gray-900 dark:text-white line-clamp-1"
          v-text="dictionary.category"
        />
        <div class="relative">
          <FwbButton
            type="button"
            size="sm"
            class="absolute -bottom-1.5 translate-y-1/2 opacity-0 transition duration-75 w-full group-hover:opacity-100 group-hover:translate-y-0"
            color="green"
            shadow="green"
          >
            Изучить
          </FwbButton>
        </div>
      </div>
    </FwbCard>
  </div>
</template>

<style scoped></style>
