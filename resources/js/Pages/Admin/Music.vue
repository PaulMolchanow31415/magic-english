<script setup>
import { reactive, ref } from 'vue'
import { useSearch } from '@/Composables/useSearch.js'
import { Head, router, useForm } from '@inertiajs/vue3'
import { quickEnableRef } from '@/Helpers/quickEnableRef.ts'
import { set } from '@vueuse/core'
import Toast from '@/Types/Toast.js'
import Toaster from '@/Shared/Toaster.vue'
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
import Pagination from '@/Shared/Pagination.vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import AudioUploader from '@/Pages/Admin/Partials/AudioUploader.vue'
import LyricsEditor from '@/Pages/Admin/Partials/LyricsEditor.vue'
import SuggestComboBox from '@/Widgets/SuggestComboBox.vue'
import { useSuggest } from '@/Composables/useSuggest.js'
import SecondaryButton from '@/Shared/SecondaryButton.vue'
import NameInput from '@/Shared/NameInput.vue'
import OpacityTransition from '@/Animations/OpacityTransition.vue'
import avatarInitials from '@/Helpers/avatarInitials'

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

const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
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
  set(selectedSinger, null)
  form.reset()
}

function handleEdit(music) {
  form.id = music.id
  form.name = music.name
  form.audio = null
  form.lyrics = music.lyrics
  set(selectedSinger, music.singer)
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
        set(selectedSinger, null)
        quickEnableRef(isSaved)
        form.reset()
      },
      onError: () => quickEnableRef(isError),
      preserveScroll: true,
    })
}

function confirmDelete() {
  form.delete(route('admin.music.destroy', musicForRemoval.value.id), {
    onSuccess: () => quickEnableRef(isDeleted),
    onError: () => quickEnableRef(isError),
    onFinish: () => set(musicForRemoval, null),
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

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Песня успешно сохранена' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Песня удалена' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

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
          <div class="flex gap-6">
            <TableActionButton @click="handleEdit(music)"> Редактировать </TableActionButton>
            <TableActionButton theme="red" @click="musicForRemoval = music">
              Удалить
            </TableActionButton>
          </div>
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
      <NameInput v-model="form.name" :error-message="form.errors.name" />

      <OpacityTransition>
        <FwbP v-show="selectedSinger" class="font-medium">{{ selectedSinger?.name }}</FwbP>
      </OpacityTransition>

      <SuggestComboBox
        v-model="searchedSinger"
        @add="selectedSinger = { id: $event.id, name: $event.value }"
        :results="loadedSingers"
        label="Исполнитель песни"
      />

      <LyricsEditor v-model="form.lyrics" :error-message="form.errors.lyrics" />

      <AudioUploader v-model="form.audio" :error-message="form.errors.audio" />

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
