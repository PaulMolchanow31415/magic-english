<script setup>
import { reactive, ref } from 'vue'
import { useFlashMessages, useSearch, useSuggest } from '@/Composables'
import { Head, router, useForm } from '@inertiajs/vue3'
import { avatarInitials } from '@/Utils'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import {
  FwbAvatar,
  FwbP,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import Pagination from '@/Widgets/Pagination.vue'
import { TableActionButton, TableActionRow } from '@/Pages/Admin/Partials/TableAction'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import AudioUploader from '@/Pages/Admin/Partials/AudioUploader.vue'
import LyricsEditor from '@/Pages/Admin/Partials/LyricsEditor.vue'
import SuggestComboBox from '@/Widgets/SuggestComboBox.vue'
import SecondaryButton from '@/Shared/SecondaryButton.vue'
import NameInput from '@/Shared/NameInput.vue'
import OpacityTransition from '@/Shared/Animations/OpacityTransition.vue'

const props = defineProps({
  musics: Object,
  filters: Object,
})

const searchedMusic = useSearch(props.filters.search)
// prettier-ignore
const {
  searched: searchedSinger,
  results: loadedSingers,
} = useSuggest('api.author-song.list', 'name')

const { showMessage } = useFlashMessages({ closable: true })
const musicForRemoval = ref(null)
const selectedSinger = ref()

const form = useForm({
  id: null,
  singer_id: null,
  name: '',
  lyrics: '',
  audio: null,
})

const editable = reactive({
  isShowModal: false,
  audio_url: '',
})

function handleCreate() {
  editable.isShowModal = true
  editable.audio_url = null
  selectedSinger.value = null
  form.reset()
}

function handleEdit(music) {
  form.id = music.id
  form.name = music.name
  form.audio = null
  form.lyrics = music.lyrics
  selectedSinger.value = music.singer
  editable.isShowModal = true
  editable.audio_url = music.audio_url
}

function confirmUpdate() {
  form
    .transform((data) => ({
      ...data,
      singer_id: selectedSinger.value.id,
    }))
    .post(route('admin.music.store'), {
      onSuccess: () => {
        editable.isShowModal = false
        editable.audio_url = null
        selectedSinger.value = null
        showMessage('Песня успешно сохранена', 'success')
        form.reset()
      },
      onError: () => showMessage('Ошибка', 'warning'),
      preserveScroll: true,
    })
}

function confirmDelete() {
  form.delete(route('admin.music.destroy', musicForRemoval.value.id), {
    onSuccess: () => showMessage('Песня удалена', 'success'),
    onError: () => showMessage('Ошибка', 'warning'),
    onFinish: () => (musicForRemoval.value = null),
    preserveScroll: true,
    preserveState: true,
  })
}

function deleteAudio() {
  router.put(
    route('admin.music.delete-audio'),
    { filename: editable.audio_url },
    {
      onSuccess: () => (editable.audio_url = null),
      preserveScroll: true,
    },
  )
}
</script>

<template>
  <Head title="Музыка" />

  <TableHeader
    v-model:searched-value="searchedMusic"
    search-placeholder="Найти песню"
    addable
    @add="handleCreate"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Автор'" />
      <FwbTableHeadCell v-text="'Название'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="music in musics.data" :key="music.id" class="group">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="music.singer.name"
            :img="music.singer.poster_url"
            :initials="avatarInitials(music.singer.name)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="music.singer.name" />
        <FwbTableCell v-text="music.name" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <TableActionRow>
            <TableActionButton @click="handleEdit(music)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="musicForRemoval = music">
              Удалить
            </TableActionButton>
          </TableActionRow>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="musics" />

  <!-- Edit modal -->
  <UpdateModal
    size="5xl"
    title="Обновление данных песни"
    :show="editable.isShowModal"
    :loading="form.processing"
    @confirm="confirmUpdate"
    @close="editable.isShowModal = false"
  >
    <div class="space-y-6">
      <NameInput v-model="form.name" :error="form.errors.name" />

      <OpacityTransition>
        <FwbP v-show="selectedSinger" class="font-medium">{{ selectedSinger?.name }}</FwbP>
      </OpacityTransition>

      <SuggestComboBox
        v-model="searchedSinger"
        @add="selectedSinger = { id: $event.id, name: $event.value }"
        :results="loadedSingers"
        label="Исполнитель песни"
      />

      <LyricsEditor v-model="form.lyrics" :error="form.errors.lyrics" />

      <AudioUploader v-model="form.audio" :error="form.errors.audio" />

      <OpacityTransition>
        <SecondaryButton
          v-show="editable.audio_url"
          @click="deleteAudio"
          v-text="'Удалить аудио'"
        />
      </OpacityTransition>
    </div>
  </UpdateModal>

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!musicForRemoval"
    @confirm="confirmDelete"
    @close="musicForRemoval = null"
  >
    <template #message v-if="musicForRemoval">
      Вы уверены, что хотите удалить песню:
      <b>{{ musicForRemoval.name }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>
