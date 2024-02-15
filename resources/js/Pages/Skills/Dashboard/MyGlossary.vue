<script setup>
import { FwbButton } from 'flowbite-vue'
import VocabularyList from '@/Pages/Skills/Patials/VocabularyList.vue'
import AutoHead from '@/Shared/AutoHead.vue'
import SuggestComboBox from '@/Shared/SuggestComboBox.vue'
import useSuggest from '@/Composables/useSuggest.js'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'
import FilterSelect from '@/Pages/Skills/Patials/FilterSelect.vue'
import useFilter from '@/Composables/useFilter.js'

const props = defineProps({
  vocabularies: Array,
  selectedFilter: String,
  filters: Array,
})

const { searched, results } = useSuggest('api.vocabulary.list')
const selectedFilter = useFilter('student.vocabularies.dashboard', props.selectedFilter)

const isEmpty = ref(false)

function add(event) {
  router.post(route('student.add-vocabulary', { word: event.value }), null, {
    onSuccess: () => router.reload(),
  })
}

function train() {
  if (props.vocabularies.length) {
    router.visit(route('student.vocabularies.challenge'))
    return
  }

  useQuickEnableRef(isEmpty)
}
</script>

<template>
  <AutoHead />

  <Toaster
    :tosts="[
      new Toast({ type: 'info', isShow: isEmpty, value: 'У вас не добалены слова к изучению' }),
    ]"
  />

  <section
    class="group flex items-center justify-between gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
  >
    <div class="grow">
      <SuggestComboBox
        class="max-w-sm"
        v-model="searched"
        label="Добавление слова"
        placeholder="Название на английском"
        :results="results"
        @add="add"
      />
      <div class="mt-3 flex gap-2.5">
        <FilterSelect v-model="selectedFilter" :filters="filters" />
        <FwbButton
          @click="router.delete(route('student.flush-vocabularies'), { preserveScroll: true })"
          size="sm"
          color="alternative"
          type="button"
          class="py-2"
        >
          <Icon :icon="['far', 'trash-can']" class="me-1" />
          Удалить весь словарь
        </FwbButton>
      </div>
    </div>

    <FwbButton
      @click="train"
      size="lg"
      gradient="green-blue"
      type="button"
      class="md:px-36 md:py-3 font-bold px-12 me-4"
    >
      Тренировать
    </FwbButton>
  </section>

  <VocabularyList :vocabularies="vocabularies" removable />
</template>
