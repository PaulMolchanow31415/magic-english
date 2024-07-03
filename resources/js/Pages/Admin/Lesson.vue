<script setup>
import { useFlashMessages, useSearch } from '@/Composables'
import { ref } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import Pagination from '@/Widgets/Pagination.vue'
import {
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import { TableActionButton, TableActionRow } from '@/Pages/Admin/Partials/TableAction'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import NumberInput from '@/Pages/Admin/Partials/NumberInput.vue'
import ComplexitySelect from '@/Pages/Admin/Partials/ComplexitySelect.vue'

const props = defineProps({
  lessons: Object,
  filters: Object,
  prevLessonNumber: Number,
})

const page = usePage()
const searchedLesson = useSearch(props.filters.search)
const number = ref(props.prevLessonNumber)
const lessonForRemoval = ref(null)
const isShowEditModal = ref(false)
const { showMessage } = useFlashMessages({ closable: true })

const form = useForm({
  id: null,
  number: number.value + 1,
  content: '',
  complexity: page.props.complexities[0],
})

function handleCreate() {
  form.id = null
  form.number = number.value + 1
  form.content = ''
  form.complexity = page.props.complexities[0]
  isShowEditModal.value = true
}

function handleEdit(lesson) {
  form.id = lesson.id
  form.number = lesson.number || number.value + 1
  form.content = lesson.content
  form.complexity = lesson.complexity
  isShowEditModal.value = true
}

function confirmUpdate() {
  form.post(route('admin.lesson.store'), {
    onSuccess() {
      showMessage('Урок успешно сохранен', 'success')
      isShowEditModal.value = false
      form.reset()
      number.value++
    },
    onError: () => showMessage('Ошибка', 'warning'),
    preserveScroll: true,
  })
}

function confirmDelete() {
  form.delete(route('admin.lesson.destroy', { id: lessonForRemoval.value.id }), {
    onSuccess: () => {
      lessonForRemoval.value = null
      showMessage('Урок удален', 'success')
    },
    onError: () => showMessage('Ошибка', 'warning'),
    preserveScroll: true,
    preserveState: true,
  })
}
</script>

<template>
  <Head title="Уроки" />

  <TableHeader
    v-model:searched-value="searchedLesson"
    search-placeholder="Найти урок"
    addable
    @add="handleCreate"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Номер урока'" />
      <FwbTableHeadCell v-text="'Дата создания'" />
      <FwbTableHeadCell v-text="'Сложность'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="lesson in lessons.data" :key="lesson.id" class="group">
        <FwbTableCell v-text="lesson.number" />
        <FwbTableCell v-text="$helpers.formatTimestamp(lesson.created_at)" />
        <FwbTableCell v-text="lesson.complexity" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <TableActionRow class="pe-4">
            <TableActionButton @click="handleEdit(lesson)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="lessonForRemoval = lesson">
              Удалить
            </TableActionButton>
          </TableActionRow>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="lessons" />

  <UpdateModal
    size="4xl"
    title="Обновление урока"
    :show="isShowEditModal"
    :loading="form.processing"
    @confirm="confirmUpdate"
    @close="isShowEditModal = false"
  >
    <NumberInput
      v-model="form.number"
      label="Номер урока"
      placeholder="999"
      class="mb-4 max-w-[10rem]"
      :error="form.errors.number"
    />

    <ComplexitySelect
      class="mb-6"
      label="Сложность урока"
      v-model="form.complexity"
      :error="form.errors.complexity"
    />

    <TextRedactor
      v-model="form.content"
      toolbar-style="full"
      placeholder="Текст урока"
      :error="form.errors.content"
    />
  </UpdateModal>

  <DeleteConfirmationModal
    :show="!!lessonForRemoval"
    @confirm="confirmDelete"
    @close="lessonForRemoval = null"
  >
    <template #message v-if="lessonForRemoval">
      Вы уверены, что хотите удалить урок
      <b>{{ lessonForRemoval.name }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>
</template>
