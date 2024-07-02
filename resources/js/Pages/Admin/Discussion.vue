<script setup>
import { FwbAvatar, FwbHeading } from 'flowbite-vue'
import { Head, router } from '@inertiajs/vue3'
import Badge from '@/Shared/Badge.vue'
import { avatarInitials } from '@/Utils'
import { Accordion, AccordionItem } from '@/Shared/Accordion'
import { useFlashMessages } from '@/Composables'

const props = defineProps({
  discussions: Array,
  reportedComments: Array,
})

const { showMessage } = useFlashMessages({ closable: true })

function markAsRead(comment) {
  router.patch(
    route('admin.comment.report'),
    {
      commentId: comment.id,
      isReported: false,
    },
    {
      onSuccess: () => showMessage('Комментарий закрыт', 'success'),
      onError: () => showMessage('Ошибка', 'warning'),
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
        showMessage('Пользователь успешно заблокирован!', 'success')
        markAsRead(comment)
      },
      onError: () => showMessage('Ошибка', 'warning'),
      preserveScroll: true,
    },
  )
}

if (!props.reportedComments.length) {
  showMessage('Для данного обсуждения не составлены комментарии', 'info', {
    timeout: 3000,
    closable: true,
  })
}
</script>

<template>
  <Head title="Обсуждения" />

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
  <FwbHeading class="m-4 mt-8 text-center" v-else tag="h6"
    >Жалобы на комментарии пользователей не найдены</FwbHeading
  >
</template>

<style scoped></style>
