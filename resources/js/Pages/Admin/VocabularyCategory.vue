<script setup>
import Toast from '@/Classes/Toast.js'
import { Head, router, useForm } from '@inertiajs/vue3'
import Toaster from '@/Shared/Toaster.vue'
import { ref } from 'vue'
import TableHeader from '@/Shared/TableHeader.vue'
import { set, useEventListener, watchThrottled } from '@vueuse/core'
import {
  FwbInput,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import Pagination from '@/Shared/Pagination.vue'
import Tooltip from '@/Shared/Tooltip.vue'
import { useQuickEnableRef } from '@/Composables/index.js'

const props = defineProps({
  categories: Object,
  filters: Array,
})

const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const searchedCategory = ref(props.filters.search || '')
const isShowEditCell = ref(false)
const form = useForm({
  id: null,
  name: '',
  updated_at: null,
  created_at: null,
})

watchThrottled(
  searchedCategory,
  (value) =>
    router.get(
      route('admin.vocabulary-category.index'),
      { search: `${value}` },
      {
        preserveState: true,
        preserveScroll: true,
        replace: true,
      },
    ),
  { throttle: 500 },
)

function save() {
  form.post(route('admin.vocabulary-category.index'), {
    onSuccess: () => useQuickEnableRef(isSaved),
    onError: () => useQuickEnableRef(isError),
    onFinish: () => {
      set(isShowEditCell, false)
      form.reset()
    },
  })
}

function onKeyPressed(event) {
  if (event.key === 'Enter') {
    save()
  }
}

useEventListener(document, 'keypress', onKeyPressed)
</script>

<template>
  <Head title="Категории слов" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Категория слова успешно добавлена' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Категория успешно удалена!' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <TableHeader
    v-model:searched-value="searchedCategory"
    search-placeholder="Найти категорию"
    addable
    @add="isShowEditCell = true"
  />

  <FwbTable striped-columns>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Идентификатор'" />
      <FwbTableHeadCell v-text="'Название'" />
      <FwbTableHeadCell v-text="'Дата последнего изменения'" />
      <FwbTableHeadCell v-text="'Дата создания'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-if="isShowEditCell">
        <FwbTableCell v-text="form.id || 'Пусто'" />
        <FwbTableCell>
          <Tooltip>
            <template #trigger>
              <FwbInput v-model="form.name" placeholder="Название категории" size="sm" />
            </template>
            <template #content>
              <p class="leading-loose">Для сохраниния нажмите&nbsp;&nbsp;<kbd>Enter</kbd></p>
            </template>
          </Tooltip>
        </FwbTableCell>
        <FwbTableCell v-text="form.updated_at || 'Пусто'" />
        <FwbTableCell v-text="form.created_at || 'Пусто'" />
      </FwbTableRow>
      <template v-else>
        <FwbTableRow v-for="category in categories.data">
          <FwbTableCell v-text="category.id" />
          <FwbTableCell v-text="category.name">
            <!--            <FwbTableCell>
              <Tooltip>
                <template #trigger>{{ category.name }}</template>
                <template #content>
                  <p class="leading-loose">Для сохраниния нажмите&nbsp;&nbsp;<kbd>Enter</kbd></p>
                </template>
              </Tooltip>
            </FwbTableCell>-->
          </FwbTableCell>
          <FwbTableCell v-text="category.updated_at" />
          <FwbTableCell v-text="category.created_at" />
        </FwbTableRow>
      </template>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="categories" />
</template>

<style scoped></style>