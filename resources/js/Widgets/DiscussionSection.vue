<script>
import { defineComponent } from 'vue'
import { FwbA, FwbButton, FwbTextarea } from 'flowbite-vue'
import DiscussionComment from '@/Widgets/DiscussionComment.vue'
import { router, useForm } from '@inertiajs/vue3'

export default defineComponent({
  name: 'DiscussionSection',
  components: { DiscussionComment, FwbA, FwbButton, FwbTextarea },
  props: {
    discussionId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      discussion: { comments: [] },
    }
  },
  created() {
    this.loadComments()
  },
  setup(props) {
    return {
      form: useForm({
        message: '',
        reply_to_id: null,
        discussion_id: props.discussionId,
      }),
    }
  },
  methods: {
    loadComments() {
      axios
      .get(route('discussion.show', { id: this.discussionId }))
      .then((res) => (this.discussion = res.data))
    },
    onReply(comment) {
      this.$refs.commentForm.scrollIntoView({ behavior: 'smooth' })
      this.form.reply_to_id = comment.id
    },
    onDelete(comment) {
      router.delete(route('comment.destroy', { id: comment.id }), {
        onSuccess: this.loadComments,
        preserveScroll: true,
      })
    },
    onReport(comment) {
      router.patch(route('discussion.report'), { commentId: comment.id }, { preserveScroll: true })
    },
    submit() {
      this.form.post(route('comment.store'), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset()
          this.loadComments()
        },
      })
    },
  },
  computed: {
    sortedComments() {
      const { comments } = this.discussion
      const result = []
      const indexed = new Set()
      let anchor
      let repliers

      for (let i = 0; i < comments.length; i++) {
        if (indexed.has(i)) {
          continue
        }

        anchor = comments[i]

        repliers = comments.filter((comment, index) => {
          if (comment.reply_to_id === anchor.id && !indexed.has(index)) {
            indexed.add(index)
            return true
          }
        })

        result.push(anchor, ...repliers)
      }

      return result
    },
  },
})
</script>

<template>
  <section class="bg-white dark:bg-gray-900 py-8 lg:py-16 antialiased">
    <div class="max-w-2xl mx-auto px-4">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">
          Обсуждение ({{ discussion.comments.length }})
        </h2>
      </div>

      <!--  Comment form  -->
      <form ref="commentForm" @submit.prevent="submit" method="post" class="space-y-4">
        <FwbTextarea
          v-model="form.message"
          :rows="3"
          custom
          label="Ваше сообщение"
          placeholder="Текст комментария..."
          class="focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
        >
          <template #footer>
            <FwbButton type="submit">Опубликовать</FwbButton>
          </template>
        </FwbTextarea>
      </form>
      <p class="ml-auto text-xs text-gray-500 dark:text-gray-400">
        Нажимая "опубликовать", вы соглашаетесь с нашей
        <FwbA color="text-blue-500" :href="route('policy.show')">
          политикой конфиденциальности
        </FwbA>
      </p>

      <!--  Comments  -->
      <div class="space-y-6 mt-6">
        <DiscussionComment
          v-for="comment of sortedComments"
          :key="comment.id"
          :comment="comment"
          @reply="onReply"
          @delete="onDelete"
          @report="onReport"
        />
      </div>
    </div>
  </section>
</template>
