<script setup>
import { useQuickEnableRef, useSearch } from '@/Composables/index.js'
import Toast from '@/Classes/Toast.js'
import Toaster from '@/Shared/Toaster.vue'
import TableHeader from '@/Shared/TableHeader.vue'
import { computed, nextTick, reactive, ref } from 'vue'
import {
  FwbAvatar,
  FwbFileInput,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import Pagination from '@/Shared/Pagination.vue'
import Badge from '@/Shared/Badge.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import UpdateModal from '@/Shared/Admin/UpdateModal.vue'
import TextInput from '@/Shared/TextInput.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import Tooltip from '@/Shared/Tooltip.vue'
import SecondaryButton from '@/Shared/SecondaryButton.vue'

const props = defineProps({
  dictionary: Object,
  filters: Object,
})

const isSaved = ref(false)
const isDeleted = ref(false)
const isWordDeletionError = ref(false)
const isSavingError = ref(false)
const searchedVocabulary = useSearch('admin.vocabulary.index', props.filters.search)
const form = useForm({
  id: null,
  en: '',
  translations: [],
  photo: null,
  photo_external_path: null,
})
const editable = reactive({
  isShowModal: false,
  translations: new Set(),
  item: '',
  poster_url: '',
})
const wordInput = ref(null)

const editableTranslation = computed({
  get: () => editable.item,
  set(str) {
    editable.item = str
      .trim()
      .toLowerCase()
      .replace(/[^А-я\s]/g, '')
  },
})
const editableWord = computed({
  get: () => form.en,
  set(str) {
    form.en = str
      .trim()
      .toLowerCase()
      .replace(/[^A-Za-z\s]/g, '')
  },
})

function handleAdd() {
  editable.item = ''
  editable.translations = new Set()
  editable.isShowModal = true
  editable.poster_url = null
  form.photo_external_path = ''
  form.en = ''
  nextTick(() => wordInput.value.focus())
}

function handleEdit(v) {
  form.id = v.id
  form.en = v.en
  editable.poster_url = v.poster_url
  editable.translations = new Set(v.translations)
  editable.isShowModal = true
  nextTick(() => wordInput.value.focus())
}

function handleAddTranslation() {
  if (editable.item.length > 0) {
    editable.translations.add(editable.item)
    editable.item = ''
  }
}

function remove(vocabulary) {
  form.delete(route('admin.vocabulary.destroy', { id: vocabulary.id }), {
    onSuccess: () => useQuickEnableRef(isDeleted),
    onError: () => useQuickEnableRef(isWordDeletionError),
    preserveScroll: true,
    preserveState: true,
  })
}

function deletePoster() {
  router.put(
    route('admin.vocabulary.poster.destroy'),
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

function removeTranslation(vocabulary, translation) {
  router.put(
    route('admin.vocabulary.translation.destroy'),
    {
      vocabularyId: vocabulary.id,
      removalTranslation: translation,
    },
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}

function confirmEdit() {
  form.translations = Array.from(editable.translations)

  form.post(route('admin.vocabulary.store'), {
    onSuccess: () => useQuickEnableRef(isSaved),
    onError: () => useQuickEnableRef(isSavingError),
    onFinish: () => {
      editable.isShowModal = false
      form.id = null
      form.en = ''
      form.photo = null
      form.photo_external_path = null
      form.reset()
    },
    preserveScroll: true,
  })
}
</script>

<template>
  <Head title="Лексика" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Слово с переводом добавлено' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Слово удалено' }),
      new Toast({
        type: 'warning',
        isShow: isWordDeletionError,
        value: 'Ошибка, не удалось удалить слово',
      }),
      new Toast({
        type: 'warning',
        isShow: isSavingError,
        value: 'Ошибка, не удалось сохранить слово',
      }),
    ]"
  />

  <TableHeader
    v-model:searched-value="searchedVocabulary"
    search-placeholder="Поиск лексики"
    addable
    @add="handleAdd"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Слово'" />
      <FwbTableHeadCell v-text="'Переводы'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="vocabulary in dictionary.data">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="vocabulary.en"
            :img="vocabulary.poster_url"
            :initials="vocabulary.en.charAt(0)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="vocabulary.en" />
        <FwbTableCell class="overflow-x-auto flex items-center pr-5 py-4.5 max-w-[40vw]">
          <Badge
            v-for="translation in vocabulary.translations"
            closable
            @close="removeTranslation(vocabulary, translation)"
          >
            <span class="line-clamp-1" :title="translation">{{ translation }}</span>
          </Badge>
        </FwbTableCell>
        <FwbTableCell>
          <div class="flex gap-6">
            <button
              @click="handleEdit(vocabulary)"
              type="button"
              class="text-blue-600 dark:text-blue-400 hover:underline"
            >
              Редактировать
            </button>
            <button
              @click="remove(vocabulary)"
              type="button"
              class="text-red-600 dark:text-red-400 hover:underline"
            >
              Удалить
            </button>
          </div>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="dictionary" />

  <!-- Edit modal -->
  <UpdateModal
    size="md"
    title="Обновление слова"
    :show="editable.isShowModal"
    :loading="form.processing"
    @confirm="confirmEdit"
    @close="editable.isShowModal = false"
    @press-enter="handleAddTranslation"
  >
    <div class="space-y-6">
      <InputLabel value="Слово на английском">
        <TextInput class="w-full" ref="wordInput" v-model="editableWord" />
      </InputLabel>
      <div class="flex items-start flex-wrap gap-y-2">
        <Badge
          v-for="translation in editable.translations"
          :key="translation"
          size="lg"
          closable
          @close="editable.translations.delete(translation)"
        >
          {{ translation }}
        </Badge>
        <Badge theme="yellow" v-show="editable.item" size="lg">
          {{ editable.item }}
        </Badge>
      </div>
      <InputLabel class="mt-2 block" value="Добавить перевод">
        <Tooltip placement="right">
          <template #trigger>
            <TextInput class="w-full" v-model="editableTranslation" />
          </template>
          <template #content>
            <p class="leading-loose">Для добавления нажмите&nbsp;<kbd>Enter</kbd></p>
          </template>
        </Tooltip>
      </InputLabel>
      <FwbFileInput v-model="form.photo" dropzone />
      <InputLabel class="mt-2 block" value="Или введите ссылку на изображение">
        <TextInput v-model="form.photo_external_path" class="w-full" name="url" />
      </InputLabel>
      <SecondaryButton
        v-if="editable.poster_url"
        type="button"
        class="mt-2"
        @click.prevent="deletePoster"
      >
        Удалить фотографию
      </SecondaryButton>
    </div>
  </UpdateModal>
</template>

<style scoped></style>