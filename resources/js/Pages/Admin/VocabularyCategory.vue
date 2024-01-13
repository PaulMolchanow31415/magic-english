<script setup>
import Toast from '@/Classes/Toast.js'
import { Head, useForm } from '@inertiajs/vue3'
import Toaster from '@/Shared/Toaster.vue'
import { computed, ref } from 'vue'
import TableHeader from '@/Shared/TableHeader.vue'
import { set, useDateFormat, useEventListener } from '@vueuse/core'
import {
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import Pagination from '@/Shared/Pagination.vue'
import Tooltip from '@/Shared/Tooltip.vue'
import { useQuickEnableRef, useSearch } from '@/Composables/index.js'
import TextInput from '@/Shared/TextInput.vue'

const props = defineProps({
  categories: Object,
  filters: Array,
})

const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const isShowEditCell = ref(false)
const form = useForm({ id: null, name: '' })
const searchedCategory = useSearch(props.filters.search, 'admin.vocabulary-category.index')

const categoryName = computed({
  get: () => form.name,
  set(value) {
    form.name = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase()
  },
})

function reset() {
  form.reset()
  form.name = ''
}

function save() {
  form.post(route('admin.vocabulary-category.index'), {
    onSuccess: () => useQuickEnableRef(isSaved),
    onError: () => useQuickEnableRef(isError),
    onFinish: () => {
      set(isShowEditCell, false)
      reset()
    },
  })
}

function remove() {
  form.delete(route('admin.vocabulary-category.destroy', { id: form.id }), {
    onSuccess: () => {
      set(isShowEditCell, false)
      useQuickEnableRef(isDeleted)
      reset()
    },
    onError: () => useQuickEnableRef(isError),
  })
}

function handleCreate() {
  reset()
  set(isShowEditCell, true)
}

function editCell(category) {
  scrollTo(0, 0)
  form.id = category.id
  form.name = category.name
  form.updated_at = category.updated_at
  form.created_at = category.created_at
  set(isShowEditCell, true)
}

const formatDate = (timestamp) =>
  useDateFormat(timestamp, 'YYYY-MM-DD hh:mm', { locales: 'ru' }).value.replace(/"/g, '')

function handleKeyDown(e) {
  if (!isShowEditCell.value) {
    return
  }

  if (e.key === 'Enter' && !e.shiftKey) {
    save()
  } else if (e.ctrlKey && e.code === 'KeyD' && form.id) {
    e.preventDefault()
    remove()
  }
}

useEventListener(document, 'keydown', handleKeyDown)
</script>

<template>
  <Head title="Категории слов" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Категория слова успешно добавлена' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Категория успешно удалена!' }),
      new Toast({ type: 'warning', isShow: isError, value: form.errors.name || 'Ошибка' }),
    ]"
  />

  <TableHeader
    v-model:searched-value="searchedCategory"
    search-placeholder="Найти категорию"
    addable
    @add="handleCreate"
  />

  <FwbTable striped-columns hoverable>
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
              <TextInput
                v-model="categoryName"
                placeholder="Название категории"
                class="w-full"
                autofocus
              />
            </template>
            <template #content>
              <div class="text-left space-y-1">
                <p class="leading-loose">
                  Для сохраниния нажмите&nbsp;&nbsp;<kbd class="p-1">Enter</kbd>
                </p>
                <p v-show="form.id" class="leading-loose text-red-500">
                  Чтобы удалить нажмите&nbsp;&nbsp;<kbd class="p-1">Ctrl</kbd> +
                  <kbd class="p-1">D</kbd>
                </p>
              </div>
            </template>
          </Tooltip>
        </FwbTableCell>
      </FwbTableRow>
      <FwbTableRow v-for="category in categories.data">
        <FwbTableCell v-text="category.id" />
        <FwbTableCell>
          <Tooltip>
            <template #trigger>
              <button type="button" class="p-2 text-left" @dblclick="editCell(category)">
                {{ category.name }}
              </button>
            </template>
            <template #content>
              <p class="leading-loose">
                Для редактирования 2 раза нажмите&nbsp;&nbsp;<kbd class="p-1">Лкм</kbd>
              </p>
            </template>
          </Tooltip>
        </FwbTableCell>
        <FwbTableCell v-text="formatDate(category.updated_at)" />
        <FwbTableCell v-text="formatDate(category.created_at)" />
        <FwbTableCell />
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="categories" />
</template>

<style scoped></style>