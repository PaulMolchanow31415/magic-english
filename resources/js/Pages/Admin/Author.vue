<script setup>
import { reactive, ref } from 'vue'
import { useSearch } from '@/Composables/useSearch.js'
import { Head, router, useForm } from '@inertiajs/vue3'
import { quickEnableRef } from '@/Helpers/quickEnableRef.ts'
import { set } from '@vueuse/core'
import Toast from '@/Types/Toast.ts'
import Toaster from '@/Shared/Toaster.vue'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import {
  FwbAvatar,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import Pagination from '@/Shared/Pagination.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import PhotoUploader from '@/Pages/Admin/Partials/PhotoUploader.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import NameInput from '@/Shared/NameInput.vue'
import avatarInitials from '@/Helpers/avatarInitials'

const props = defineProps({
  authors: Object,
  filters: Object,
})

const searchedAuthor = useSearch(props.filters.search)

const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const authorForRemoval = ref(null)

const form = useForm({
  id: null,
  name: '',
  biography: '',
  photo: null,
  photo_external_path: null,
})

const editable = reactive({
  isShowModal: false,
  poster_url: '',
})

function handleCreate() {
  editable.isShowModal = true
  editable.poster_url = null
  form.reset()
}

function handleEdit(author) {
  form.id = author.id
  form.name = author.name
  form.biography = author.biography
  form.photo = null
  form.photo_external_path = author.photo_external_path
  editable.isShowModal = true
  editable.poster_url = author.poster_url
}

function confirmUpdate() {
  form.post(route('admin.author.store'), {
    onSuccess: () => {
      editable.isShowModal = false
      editable.poster_url = null
      quickEnableRef(isSaved)
      form.reset()
    },
    onError: () => quickEnableRef(isError),
    preserveScroll: true,
  })
}

function confirmDelete() {
  form.delete(route('admin.author.destroy', authorForRemoval.value.id), {
    onSuccess: () => quickEnableRef(isDeleted),
    onError: () => quickEnableRef(isError),
    onFinish: () => set(authorForRemoval, null),
    preserveScroll: true,
    preserveState: true,
  })
}

function deletePoster() {
  router.put(
    route('admin.author-song.delete-poster'),
    { filename: editable.poster_url },
    {
      onSuccess: () => {
        editable.poster_url = null
        form.photo_external_path = null
      },
      preserveScroll: true,
    },
  )
}
</script>

<template>
  <Head title="Авторы песен" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Автор успешно сохранен' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Автор удален' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <TableHeader
    v-model:searched-value="searchedAuthor"
    search-placeholder="Найти автора"
    addable
    @add="handleCreate"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Имя автора'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="author in authors.data" :key="author.id" class="group">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="author.name"
            :img="author.poster_url"
            :initials="avatarInitials(author.name)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="author.name" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <div class="flex gap-6">
            <TableActionButton @click="handleEdit(author)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="authorForRemoval = author">
              Удалить
            </TableActionButton>
          </div>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="authors" />

  <!-- Edit modal -->
  <UpdateModal
    size="4xl"
    title="Обновление данных об авторе"
    :show="editable.isShowModal"
    :loading="form.processing"
    @confirm="confirmUpdate"
    @close="editable.isShowModal = false"
  >
    <div class="space-y-6">
      <NameInput v-model="form.name" label="Полное имя" :error-message="form.errors.name" />

      <TextRedactor
        toolbar-style="full"
        v-model="form.biography"
        placeholder="Биография"
        :error-message="form.errors.biography"
      />

      <PhotoUploader
        v-model="form.photo"
        v-model:external-path="form.photo_external_path"
        :has-photo="!!editable.poster_url"
        @delete-photo="deletePoster"
      />
    </div>
  </UpdateModal>

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!authorForRemoval"
    @confirm="confirmDelete"
    @close="authorForRemoval = null"
  >
    <template #message v-if="authorForRemoval">
      Вы уверены, что хотите удалить автора:
      <b>{{ authorForRemoval.name }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>
