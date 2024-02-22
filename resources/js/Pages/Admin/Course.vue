<script setup>
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import Toast from '@/Classes/Toast.js'
import Pagination from '@/Shared/Pagination.vue'
import {
  FwbAvatar,
  FwbInput,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import Toaster from '@/Shared/Toaster.vue'
import { inject, reactive, ref } from 'vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import { useSearch } from '@/Composables/useSearch.js'
import InputLabel from '@/Shared/InputLabel.vue'
import PhotoUploader from '@/Pages/Admin/Partials/PhotoUploader.vue'
import ComplexitySelect from '@/Pages/Admin/Partials/ComplexitySelect.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'
import { set } from '@vueuse/core'
import GrammarModal from '@/Pages/Admin/Partials/GrammarModal.vue'

const props = defineProps({
  courses: Object,
  filters: Object,
})

const avatarInitials = inject('avatarInitials')

const page = usePage()
const searchedCourse = useSearch(props.filters.search)
const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const courseForRemoval = ref(null)

const editable = reactive({
  poster_url: null,
  isShowModal: false,
})

const selectedId = ref(0)

const form = useForm({
  id: null,
  name: null,
  description: null,
  complexity: page.props.complexities[0],
  photo: null,
  photo_external_path: null,
})

function handleCreate() {
  editable.isShowModal = true
  editable.poster_url = null
}

function handleEdit(course) {
  form.id = course.id
  form.name = course.name
  form.description = course.description
  form.complexity = course.complexity
  editable.isShowModal = true
  editable.poster_url = null
}

function confirmUpdate() {
  form.post(route('admin.course.store'), {
    onSuccess: () => {
      useQuickEnableRef(isSaved)
      editable.isShowModal = false
      editable.poster_url = null
      form.reset()
    },
    onError: () => useQuickEnableRef(isError),
  })
}

function confirmDelete() {
  form.delete(route('admin.course.destroy', { id: courseForRemoval.value.id }), {
    onSuccess: () => {
      set(courseForRemoval, null)
      useQuickEnableRef(isDeleted)
    },
    onError: () => useQuickEnableRef(isError),
    preserveState: true,
    preserveScroll: true,
  })
}

function deletePoster() {
  router.put(
    route('admin.course.delete-poster'),
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
  <Head title="Курсы" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Курс успешно сохранен' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Курс удален' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <TableHeader
    v-model:searched-value="searchedCourse"
    search-placeholder="Найти курс"
    addable
    @add="handleCreate"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Название'" />
      <FwbTableHeadCell v-text="'Описание'" />
      <FwbTableHeadCell v-text="'Сложность'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="course in courses.data" :key="course.id" class="group">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="course.name"
            :img="course.poster_url"
            :initials="avatarInitials(course.name)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="course.name" />
        <FwbTableCell v-text="course.description" />
        <FwbTableCell v-text="course.complexity" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <div class="flex gap-6">
            <TableActionButton theme="green" @click="selectedId = course.id">
              Грамматика
            </TableActionButton>
            <TableActionButton @click="handleEdit(course)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="courseForRemoval = course">
              Удалить
            </TableActionButton>
          </div>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="courses" />

  <UpdateModal
    size="4xl"
    title="Обновление курса"
    :show="editable.isShowModal"
    :loading="form.processing"
    @confirm="confirmUpdate"
    @close="editable.isShowModal = false"
  >
    <div class="grid grid-cols-2 gap-y-6 gap-x-3">
      <InputLabel value="Название">
        <FwbInput
          size="sm"
          v-model="form.name"
          :validation-status="form.errors.name ? 'error' : ''"
        >
          <template #validationMessage>
            {{ form.errors.name }}
          </template>
        </FwbInput>
      </InputLabel>
      <ComplexitySelect v-model="form.complexity" :error-message="form.errors.complexity" />
      <div class="col-span-2">
        <InputLabel value="Описание">
          <FwbInput
            v-model="form.description"
            :validation-status="form.errors.description ? 'error' : ''"
          >
            <template #validationMessage>
              {{ form.errors.description }}
            </template>
          </FwbInput>
        </InputLabel>
      </div>
      <PhotoUploader
        class="col-span-2"
        v-model="form.photo"
        v-model:external-path="form.photo_external_path"
        :has-photo="!!editable.poster_url"
        @delete-photo="deletePoster"
      />
    </div>
  </UpdateModal>

  <DeleteConfirmationModal
    :show="!!courseForRemoval"
    @confirm="confirmDelete"
    @close="courseForRemoval = null"
  >
    <template #message v-if="courseForRemoval">
      Вы уверены, что хотите удалить курс
      <b>{{ courseForRemoval.name }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>

  <GrammarModal :course-id="selectedId" @close="selectedId = 0" />
</template>

<style scoped></style>
