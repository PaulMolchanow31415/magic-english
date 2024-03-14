<script setup>
import Radio from '@/Shared/Radio.vue'
import { capitalize, ref, watchEffect } from 'vue'
import { router } from '@inertiajs/vue3'
import FilterSelect from '@/Pages/Skills/Patials/FilterSelect.vue'

const props = defineProps({
  filters: {
    type: Object,
    required: true,
  },
})

const selectedComplexity = ref(props.complexity)
const selectedLearnableFilter = ref(props.learnable)

watchEffect(() => {
  router.visit(
    route(route().current(), {
      filters: {
        complexity: selectedComplexity.value,
        learnable: selectedLearnableFilter.value,
      },
    }),
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
})
</script>

<template>
  <form
    class="flex flex-wrap gap-6 justify-between mb-6 px-3 py-3 text-gray-700 border border-gray-200 rounded-lg dark:border-gray-700"
  >
    <div v-if="filters.complexity" class="flex flex-wrap gap-4">
      <Radio
        v-for="complexity in $page.props.complexities"
        :label="capitalize(complexity)"
        v-model="selectedComplexity"
        :value="complexity"
        :status="complexity === selectedComplexity ? 'success' : null"
        name="complexity-filter-chooser"
        class="py-1 ps-1"
      />
    </div>

    <FilterSelect v-if="filters.learnable" v-model="selectedLearnableFilter" />
  </form>
</template>
