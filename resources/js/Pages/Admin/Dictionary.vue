<script setup>
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import { Toast } from '@/Classes'
import Toaster from '@/Shared/Toaster.vue'
import { reactive, ref } from 'vue'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
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
import Pagination from '@/Shared/Pagination.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import SuggestComboBox from '@/Widgets/SuggestComboBox.vue'
import Badge from '@/Shared/Badge.vue'
import PhotoUploader from '@/Pages/Admin/Partials/PhotoUploader.vue'
import ComplexitySelect from '@/Pages/Admin/Partials/ComplexitySelect.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { useSearch, useSuggest } from '@/Composables'
import { avatarInitials, formatTimestamp, quickEnableRef } from '@/Helpers'
import OpacityTransition from '@/Animations/OpacityTransition.vue'

const props = defineProps({
  filters: Object,
  dictionaries: Object,
})

const searchedDictionary = useSearch(props.filters.search)
const { searched: searchedVocabulary, results: vocabularies } = useSuggest('api.vocabulary.list')
const page = usePage()
const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const selectedVocabularies = ref(new Map())
const dictionaryForRemoval = ref(null)

const form = useForm({
  id: null,
  category: null,
  complexity: page.props.complexities[0],
  vocabulary_ids: [],
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
  selectedVocabularies.value.clear()
  form.reset()
}

function handleEdit(d) {
  form.id = d.id
  form.complexity = d.complexity
  form.category = d.category
  selectedVocabularies.value.clear()
  d.vocabularies.forEach((v) => selectedVocabularies.value.set(v.id, v.en))
  form.vocabulary_ids = d.vocabularies.map((v) => v.id)
  form.photo = null
  form.photo_external_path = d.photo_external_path
  editable.isShowModal = true
  editable.poster_url = d.poster_url
}

function confirmUpdate() {
  form.vocabulary_ids = Array.from(selectedVocabularies.value.keys())

  form.post(route('admin.dictionary.store'), {
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
  form.delete(route('admin.dictionary.destroy', { id: dictionaryForRemoval.value.id }), {
    onSuccess: () => quickEnableRef(isDeleted),
    onError: () => quickEnableRef(isError),
    onFinish: () => (dictionaryForRemoval.value = null),
    preserveScroll: true,
    preserveState: true,
  })
}

function deletePoster() {
  router.put(
    route('admin.dictionary.delete-poster'),
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
  <Head title="Словари" />

  <Toaster
    :toasts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Словарь успешно сохранен' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Словарь удален' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <TableHeader
    v-model:searched-value="searchedDictionary"
    search-placeholder="Найти словарь"
    addable
    @add="handleCreate"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Категория'" />
      <FwbTableHeadCell v-text="'Сложность'" />
      <FwbTableHeadCell v-text="'Дата последнего обновления'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="dictionary in dictionaries.data" :key="dictionary.id" class="group">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="dictionary.category"
            :img="dictionary.poster_url"
            :initials="avatarInitials(dictionary.category)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="dictionary.category" />
        <FwbTableCell v-text="dictionary.complexity" />
        <FwbTableCell v-text="formatTimestamp(dictionary.updated_at)" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <div class="flex gap-6">
            <TableActionButton @click="handleEdit(dictionary)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="dictionaryForRemoval = dictionary">
              Удалить
            </TableActionButton>
          </div>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="dictionaries" />

  <!-- Edit modal -->
  <UpdateModal
    size="4xl"
    title="Обновление словаря"
    :show="editable.isShowModal"
    :loading="form.processing"
    @confirm="confirmUpdate"
    @close="editable.isShowModal = false"
  >
    <div class="grid grid-cols-2 gap-y-6 gap-x-3">
      <div class="col-span-2">
        <InputLabel value="Категория">
          <FwbInput
            v-model="form.category"
            :validation-status="form.errors.category ? 'error' : ''"
          >
            <template #validationMessage>
              {{ form.errors.category }}
            </template>
          </FwbInput>
        </InputLabel>
      </div>

      <OpacityTransition>
        <div
          v-show="selectedVocabularies.size > 0"
          class="col-span-2 flex items-start flex-wrap gap-y-2 place-self-start"
        >
          <Badge
            v-for="vocabulary in selectedVocabularies"
            :key="vocabulary[0]"
            closable
            @close="selectedVocabularies.delete(vocabulary[0])"
          >
            {{ vocabulary[1] }}
          </Badge>
        </div>
      </OpacityTransition>

      <SuggestComboBox
        v-model="searchedVocabulary"
        :results="vocabularies"
        label="Добавление слова"
        @add="selectedVocabularies.set($event.id, $event.value)"
      />

      <ComplexitySelect v-model="form.complexity" :error-message="form.errors.complexity" />

      <PhotoUploader
        class="col-span-2"
        v-model="form.photo"
        v-model:external-path="form.photo_external_path"
        :has-photo="!!editable.poster_url"
        @delete-photo="deletePoster"
      />
    </div>
  </UpdateModal>

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!dictionaryForRemoval"
    @confirm="confirmDelete"
    @close="dictionaryForRemoval = null"
  >
    <template #message v-if="dictionaryForRemoval">
      Вы уверены, что хотите удалить словарь
      <b>{{ dictionaryForRemoval.category }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>
