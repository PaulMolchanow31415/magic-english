<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { FwbButton, FwbCard, FwbHeading, FwbP } from 'flowbite-vue'
import { useInfiniteScroll } from '@vueuse/core'
import { ref, toRef } from 'vue'

const props = defineProps({
  dictionaries: Object,
  learnableVocabulariesCount: Number,
})

const page = usePage()
const list = ref(null)
const allItems = toRef(props.dictionaries.data)
const initialUrl = page.url

useInfiniteScroll(
  list,
  () => {
    router.get(
      props.dictionaries.next_page_url,
      {},
      {
        onSuccess() {
          allItems.value.push(...props.dictionaries.data)
          history.replaceState({}, page.title, initialUrl)
        },
        preserveScroll: true,
        preserveState: true,
        replace: true,
      },
    )
  },
  {
    canLoadMore: () => Boolean(props.dictionaries.next_page_url),
    interval: 1000,
    distance: 10,
  },
)
</script>

<template>
  <Head title="Глоссарий" />

  <section
    @click.stop="router.visit(route('student.vocabularies.dashboard'))"
    v-if="learnableVocabulariesCount > 0"
    class="group flex items-center gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition duration-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
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
    <FwbButton size="lg" gradient="purple" class="font-bold px-12 me-4"> Повторить </FwbButton>
  </section>

  <div ref="list" class="grid grid-cols-5 gap-x-3 gap-y-4">
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