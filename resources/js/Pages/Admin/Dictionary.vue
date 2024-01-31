<script setup>
import { useQuickEnableRef, useSearch } from '@/Composables/index.js'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import Toast from '@/Classes/Toast.js'
import Toaster from '@/Shared/Toaster.vue'
import { inject, reactive, ref } from 'vue'
import TableHeader from '@/Shared/TableHeader.vue'
import {
  FwbAvatar,
  FwbHeading,
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
import { set, watchThrottled } from '@vueuse/core'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import SuggestComboBox from '@/Shared/SuggestComboBox.vue'
import SuggestListItem from '@/Classes/SuggestListItem.js'
import Badge from '@/Shared/Badge.vue'
import PhotoUploader from '@/Pages/Admin/Partials/PhotoUploader.vue'
import ComplexitySelect from '@/Pages/Admin/Partials/ComplexitySelect.vue'

const props = defineProps({
  filters: Object,
  dictionaries: Object,
})

const avatarInitials = inject('avatarInitials')

const searchedDictionary = useSearch('admin.dictionary.index', props.filters.search)
const page = usePage()
const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const searchedVocabulary = ref('')
const vocabularies = ref([])
const searchedCategory = ref('')
const categories = ref([])
const selectedVocabularies = ref(new Map())
const selectedCategory = ref()
const dictionaryForRemoval = ref(null)

const form = useForm({
  id: null,
  complexity: page.props.complexities[0],
  category_id: null,
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
  form.reset()
}

function handleEdit(d) {
  form.id = d.id
  form.complexity = d.complexity
  form.category_id = null
  set(selectedCategory, new SuggestListItem({ id: d.category.id, value: d.category.name }))
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
  form.category_id = +selectedCategory.value.id

  form.post(route('admin.dictionary.store'), {
    onSuccess: () => {
      editable.isShowModal = false
      editable.poster_url = null
      useQuickEnableRef(isSaved)
      form.reset()
    },
    onError: () => useQuickEnableRef(isError),
    preserveState: true,
    preserveScroll: true,
  })
}

function confirmDelete() {
  form.delete(route('admin.dictionary.destroy', { id: dictionaryForRemoval.value.id }), {
    onSuccess: () => useQuickEnableRef(isDeleted),
    onError: () => useQuickEnableRef(isError),
    onFinish: () => set(dictionaryForRemoval, null),
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

watchThrottled(
  searchedVocabulary,
  (v) =>
    axios.get(route('api.vocabulary.list', { search: `${v || 'a'}` })).then((res) => {
      set(
        vocabularies,
        res.data.data.map((v) => new SuggestListItem({ id: v.id, value: v.en })),
      )
    }),
  { throttle: 300 },
)

watchThrottled(
  searchedCategory,
  (v) =>
    axios.get(route('api.vocabulary-category.list', { search: `${v || 'a'}` })).then((res) => {
      set(
        categories,
        res.data.data.map((c) => new SuggestListItem({ id: c.id, value: c.name })),
      )
    }),
  { throttle: 300 },
)
</script>

<template>
  <Head title="Словари" />

  <Toaster
    :tosts="[
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
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="dictionary in dictionaries.data" :key="dictionary.id">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="dictionary.category.name"
            :img="dictionary.poster_url"
            :initials="avatarInitials(dictionary.category.name)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="dictionary.category.name" />
        <FwbTableCell v-text="dictionary.complexity" />
        <FwbTableCell>
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
      <div class="space-y-4 col-span-2">
        <FwbHeading tag="h6" v-if="selectedCategory" v-text="selectedCategory.value" />

        <SuggestComboBox
          v-model="searchedCategory"
          :results="categories"
          label="Категория словаря"
          @add="selectedCategory = $event"
        />
      </div>

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
      <b>{{ dictionaryForRemoval.category.name }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>