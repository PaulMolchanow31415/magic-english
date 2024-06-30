<script setup>
import { FwbAvatar, FwbHeading } from 'flowbite-vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import Badge from '@/Shared/Badge.vue'
import { Toast } from '@/Classes'
import Toaster from '@/Shared/Toaster.vue'
import { avatarInitials, formatTimestamp, quickEnableRef } from '@/Helpers'
import { Accordion, AccordionItem } from '@/Shared/Accordion'

defineProps({
  discussions: Array,
  reportedComments: Array,
})

const isEmptyComments = ref(false)
const userBlocked = ref(false)
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
      onSuccess: () => quickEnableRef(commentMarkAsRead),
      onError: () => quickEnableRef(isError),
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
        quickEnableRef(userBlocked)
        markAsRead(comment)
      },
      onError: () => quickEnableRef(isError),
      preserveScroll: true,
    },
  )
}
</script>

<template>
  <Head title="Обсуждения" />

  <Toaster
    :toasts="[
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
        type: 'info',
        isShow: isEmptyComments,
        value: 'Для данного обсуждения не составлены комментарии',
      }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <template v-if="reportedComments.length > 0">
    <FwbHeading class="text-center" tag="h6">Жалобы на пользователей</FwbHeading>
    <Accordion flush>
      <AccordionItem v-for="(comment, index) in reportedComments" :key="comment.id" :order="index">
        <template #heading>
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
            <time class="ms-auto me-4">{{ formatTimestamp(comment.updated_at) }}</time>
          </div>
        </template>

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
      </AccordionItem>
    </Accordion>
  </template>
  <FwbHeading class="m-4 text-center" v-else tag="h6">Жалоб пользователей пока не было</FwbHeading>
</template>

<style scoped></style>
