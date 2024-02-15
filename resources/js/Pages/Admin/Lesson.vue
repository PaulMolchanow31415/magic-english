<script setup>
import { useSearch } from '@/Composables/useSearch.js'
import { inject, ref } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import Toast from '@/Classes/Toast.js'
import Toaster from '@/Shared/Toaster.vue'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import Pagination from '@/Shared/Pagination.vue'
import {
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import { set } from '@vueuse/core'
import NumberInput from '@/Pages/Admin/Partials/NumberInput.vue'
import InputError from '@/Shared/InputError.vue'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'

const props = defineProps({
  lessons: Object,
  filters: Object,
  prevLessonNumber: Number,
})

const avatarInitials = inject('avatarInitials')

const page = usePage()
const searchedLesson = useSearch('admin.lesson.index', props.filters.search)
const number = ref(props.prevLessonNumber)
const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const lessonForRemoval = ref(null)
const isShowEditModal = ref(false)

const form = useForm({
  id: null,
  number: number.value + 1,
  content: '',
})

function handleCreate() {
  form.id = null
  form.number = number.value + 1
  form.content = ''
  set(isShowEditModal, true)
}

function handleEdit(lesson) {
  form.id = lesson.id
  form.number = lesson.number || number.value + 1
  form.content = lesson.content
  set(isShowEditModal, true)
}

function confirmUpdate() {
  form.post(route('admin.lesson.store'), {
    preserveScroll: true,
    onSuccess() {
      useQuickEnableRef(isSaved)
      set(isShowEditModal, false)
      form.reset()
      number.value++
    },
    onError: () => useQuickEnableRef(isError),
  })
}

function confirmDelete() {
  form.delete(route('admin.lesson.destroy', { id: lessonForRemoval.value.id }), {
    onSuccess: () => {
      set(lessonForRemoval, null)
      useQuickEnableRef(isDeleted)
    },
    onError: () => useQuickEnableRef(isError),
    preserveScroll: true,
    preserveState: true,
  })
}
</script>

<template>
  <Head title="Уроки" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Урок успешно сохранен' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Урок удален' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

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
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="lesson in lessons.data" :key="lesson.id">
        <FwbTableCell v-text="lesson.number" />
        <FwbTableCell v-text="lesson.created_at" />
        <FwbTableCell>
          <div class="flex gap-6 justify-end pe-4">
            <TableActionButton @click="handleEdit(lesson)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="lessonForRemoval = lesson">
              Удалить
            </TableActionButton>
          </div>
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
    <div class="mb-6">
      <InputLabel value="Номер урока">
        <NumberInput v-model="form.number" class="max-w-[10rem]" placeholder="999" />
      </InputLabel>
      <InputError :message="form.errors.number" />
    </div>

    <TextRedactor
      v-model="form.content"
      toolbar-style="full"
      placeholder="Текст урока"
      :error-message="form.errors.content"
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

<style scoped></style>