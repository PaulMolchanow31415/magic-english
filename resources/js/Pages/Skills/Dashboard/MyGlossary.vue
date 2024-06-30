<script setup>
import { FwbButton } from 'flowbite-vue'
import VocabularyList from '@/Pages/Skills/Patials/VocabularyList.vue'
import AutoHead from '@/Shared/AutoHead.vue'
import SuggestComboBox from '@/Widgets/SuggestComboBox.vue'
import { useSuggest } from '@/Composables'
import { router } from '@inertiajs/vue3'
import { ref, toRef, watch } from 'vue'
import { quickEnableRef } from '@/Helpers'
import Toaster from '@/Shared/Toaster.vue'
import { Toast } from '@/Classes'
import FilterSelect from '@/Pages/Skills/Patials/FilterSelect.vue'

const props = defineProps({
  vocabularies: Array,
  filters: Object,
})

const { searched, results } = useSuggest('api.vocabulary.list')

const filters = toRef(props, 'filters')
const selectedFilter = ref(filters.learnable)
const isEmpty = ref(false)

function add(event) {
  router.post(route('student.add-vocabulary', { word: event.value }), null, {
    onSuccess: () => router.reload(),
  })
}

function train() {
  if (props.vocabularies.length > 3) {
    router.visit(route('student.vocabularies.challenge'))
    return
  }

  quickEnableRef(isEmpty)
}

watch(selectedFilter, (learnable) => {
  router.visit(route(route().current(), { filters: { learnable } }), {
    preserveState: true,
    preserveScroll: true,
  })
})
</script>

<template>
  <AutoHead />

  <Toaster
    :toasts="[new Toast({ type: 'info', isShow: isEmpty, value: 'Слишком мало добавленных слов' })]"
  />

  <section
    class="group flex flex-wrap items-center justify-between gap-6 mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
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
      <div class="mt-3 flex flex-wrap gap-2.5">
        <FilterSelect class="max-w-none w-full sm:w-auto grow sm:grow-0" v-model="selectedFilter" />
        <FwbButton
          @click="router.delete(route('student.flush-vocabularies'), { preserveScroll: true })"
          size="sm"
          color="alternative"
          type="button"
          class="py-2 whitespace-nowrap w-full sm:w-auto grow sm:grow-0"
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
      class="md:px-26 lg:px-36 md:py-3 font-bold px-12 me-4"
    >
      Тренировать
    </FwbButton>
  </section>

  <VocabularyList :vocabularies="vocabularies" removable />
</template>
