<script setup>
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import { computed, nextTick, reactive, ref } from 'vue'
import {
  FwbAvatar,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import Pagination from '@/Widgets/Pagination.vue'
import Badge from '@/Shared/Badge.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import FocusableInput from '@/Shared/FocusableInput.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import Tooltip from '@/Shared/Tooltip.vue'
import { TableActionButton, TableActionRow } from '@/Pages/Admin/Partials/TableAction'
import PhotoUploader from '@/Pages/Admin/Partials/PhotoUploader.vue'
import { useFlashMessages, useSearch } from '@/Composables'

const props = defineProps({
  dictionary: Object,
  filters: Object,
})

const { showMessage } = useFlashMessages({ closable: true })
const searchedVocabulary = useSearch(props.filters.search)
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
    onSuccess: () => showMessage('Слово удалено', 'success'),
    onError: () => showMessage('Ошибка, не удалось удалить слово', 'warning'),
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
        showMessage('Фотография удалена!', 'success')
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
    onSuccess: () => showMessage('Слово с переводом добавлено', 'success'),
    onError: () => showMessage('Ошибка, не удалось сохранить слово', 'warning'),
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
      <FwbTableRow v-for="vocabulary in dictionary.data" class="group">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="vocabulary.en"
            :img="vocabulary.poster_url"
            :initials="$helpers.avatarInitials(vocabulary.en)"
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
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <TableActionRow>
            <TableActionButton @click="handleEdit(vocabulary)">Редактировать</TableActionButton>
            <TableActionButton @click="remove(vocabulary)" theme="red">Удалить</TableActionButton>
          </TableActionRow>
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
        <FocusableInput class="w-full" ref="wordInput" v-model="editableWord" />
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
            <FocusableInput class="w-full" v-model="editableTranslation" />
          </template>
          <template #content>
            <p class="leading-loose">Для добавления нажмите&nbsp;<kbd>Enter</kbd></p>
          </template>
        </Tooltip>
      </InputLabel>

      <PhotoUploader
        v-model="form.photo"
        v-model:external-path="form.photo_external_path"
        :has-photo="!!editable.poster_url"
        @delete-photo="deletePoster"
      />
    </div>
  </UpdateModal>
</template>

<style scoped></style>
