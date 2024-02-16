<script setup>
import {
  FwbAccordion,
  FwbAccordionContent,
  FwbAccordionHeader,
  FwbAccordionPanel,
  FwbAvatar,
  FwbHeading,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import { set, useDateFormat } from '@vueuse/core'
import { Head, router } from '@inertiajs/vue3'
import HorizontalLine from '@/Shared/HorizontalLine.vue'
import { inject, ref } from 'vue'
import Badge from '@/Shared/Badge.vue'
import Toast from '@/Classes/Toast.js'
import Toaster from '@/Shared/Toaster.vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'

defineProps({
  discussions: Array,
  reportedComments: Array,
})

const avatarInitials = inject('avatarInitials')

const discussionForRemoval = ref(null)
const userBlocked = ref(false)
const discussionDeleted = ref(false)
const commentMarkAsRead = ref(false)
const isError = ref(false)

function markAsRead(comment) {
  router.patch(
    route('admin.comment.report'),
    {
      commentId: comment.id,
      isReported: false,
    },
    {
      onSuccess: () => useQuickEnableRef(commentMarkAsRead),
      onError: () => useQuickEnableRef(isError),
      preserveScroll: true,
    },
  )
}

function handleBlock(comment) {
  router.post(
    route('admin.user.store'),
    {
      id: comment.creator_id,
      is_banned: true,
    },
    {
      onSuccess: () => {
        useQuickEnableRef(userBlocked)
        markAsRead(comment)
      },
      onError: () => useQuickEnableRef(isError),
      preserveScroll: true,
    },
  )
}

function confirmDelete() {
  router.delete(route('admin.discussion.destroy', { id: discussionForRemoval.value.id }), {
    onSuccess: () => useQuickEnableRef(discussionDeleted),
    onError: () => useQuickEnableRef(isError),
    onFinish: () => set(discussionForRemoval, null),
  })
}

const formatDate = (timestamp) =>
  useDateFormat(timestamp, 'DD MMM YYYY | HH:mm').value.replace(/"/g, '')
</script>

<template>
  <Head title="Обсуждения" />

  <Toaster
    :tosts="[
      new Toast({
        type: 'success',
        isShow: userBlocked,
        value: 'Пользователь успешно заблокирован!',
      }),
      new Toast({
        type: 'success',
        isShow: commentMarkAsRead,
        value: 'Комментарий закрыт',
      }),
      new Toast({
        type: 'success',
        isShow: discussionDeleted,
        value: 'Обсуждение удалено!',
      }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Тип модели на которую ссылается обсуждение'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="discussion in discussions" :key="discussion.id" class="group">
        <FwbTableCell v-text="discussion.discussionable_type" />
        <FwbTableCell class="opacity-0 group-hover:opacity-100 transition duration-75">
          <TableActionButton @click="discussionForRemoval = discussion" theme="red">
            Удалить
          </TableActionButton>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <template v-if="reportedComments.length > 0">
    <HorizontalLine />

    <FwbHeading class="text-center" tag="h6">Жалобы на пользователей</FwbHeading>
    <FwbAccordion flush>
      <FwbAccordionPanel v-for="comment in reportedComments" :key="comment.id">
        <FwbAccordionHeader>
          <div class="flex items-center">
            <FwbAvatar
              :alt="comment.creator.name"
              :img="comment.creator.profile_photo_url"
              rounded
              size="sm"
              :initials="avatarInitials(comment.creator.name)"
            />
            <span class="ms-2" v-text="comment.creator.name" />
            <Badge class="ms-4">{{ comment.creator.role }}</Badge>
            <time class="ms-auto me-4">{{ formatDate(comment.updated_at) }}</time>
          </div>
        </FwbAccordionHeader>
        <FwbAccordionContent>
          <div class="text-gray-500 dark:text-gray-400 accordion-content">
            {{ comment.message }}
          </div>

          <div class="accordion-button-group">
            <div class="flex gap-6">
              <button
                @click="markAsRead(comment)"
                type="button"
                class="hover:text-blue-600 hover:dark:text-blue-400 hover:underline"
              >
                Пометить прочитанным
              </button>
              <button @click="handleBlock(comment)" type="button" class="hover:text-red-600">
                Заблокировать
              </button>
            </div>
          </div>
        </FwbAccordionContent>
      </FwbAccordionPanel>
    </FwbAccordion>
  </template>

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!discussionForRemoval"
    @confirm="confirmDelete"
    @close="discussionForRemoval = null"
  >
    <template #message> Вы уверены, что хотите удалить обсуждение ? </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>
