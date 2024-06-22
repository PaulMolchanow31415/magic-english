<script>
import { capitalize, defineComponent } from 'vue'
import { FwbAvatar } from 'flowbite-vue'
import { useDateFormat } from '@vueuse/core'
import CommentActionDropdown from '@/Widgets/CommentActionDropdown.vue'

export default defineComponent({
  name: 'DiscussionComment',
  components: { CommentActionDropdown, FwbAvatar },
  setup(props) {
    return {
      dateUpdate: capitalize(useDateFormat(props.comment.updated_at, 'MMM DD, YYYY').value),
    }
  },
  inject: ['avatarInitials'],
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },
  computed: {
    currentUserIsCreator() {
      return this.comment.creator_id === this.$page.props.auth.user.id
    },
  },
  emits: ['reply', 'delete', 'report'],
})
</script>

<template>
  <article
    class="p-6 text-base bg-gray-50 rounded-lg dark:bg-gray-700"
    :class="{ 'ml-6 lg:ml-12': comment.reply_to_id }"
  >
    <footer class="flex justify-between items-center mb-2">
      <div class="flex items-center">
        <p
          class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold"
        >
          <FwbAvatar
            class="mr-2 w-6 h-6 rounded-full"
            size="xs"
            rounded
            :alt="comment.creator.name"
            :img="comment.creator.profile_photo_url"
            :initials="avatarInitials(comment.username)"
          />
          {{ comment.creator.name }}
        </p>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          <time :datetime="comment.updated_at" :title="dateUpdate">{{ dateUpdate }}</time>
        </p>
      </div>
      <CommentActionDropdown
        @delete="$emit('delete', comment)"
        @report="$emit('report', comment)"
        :can-delete="currentUserIsCreator"
        :can-report="!currentUserIsCreator"
      />
    </footer>
    <p class="text-gray-500 dark:text-gray-400" v-text="comment.message" />
    <div v-if="!comment.reply_to_id" class="flex items-center mt-4 space-x-4">
      <button
        @click="$emit('reply', comment)"
        type="button"
        class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium"
      >
        <Icon :icon="['far', 'comment-dots']" class="mr-1.5" />
        Ответить
      </button>
    </div>
  </article>
</template>
