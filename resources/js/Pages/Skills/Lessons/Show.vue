<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import { router } from '@inertiajs/vue3'
import { FwbButton } from 'flowbite-vue'
import Translatable from '@/Widgets/Translatable.vue'
import DiscussionSection from '@/Widgets/DiscussionSection.vue'

const props = defineProps({
  lesson: Object,
  nextPageUrl: String,
  prevPageUrl: String,
  canComplete: Boolean,
})

function complete() {
  // prettier-ignore
  router.post(route('student.complete-lesson', { id: props.lesson.id }), {}, {
    onSuccess: () => router.visit(props.nextPageUrl || route('skills.self-education')),
  })
}

router.post(route('student.add-lesson', { id: props.lesson.id }))
</script>

<template>
  <AutoHead />

  <article
    class="container px-1.5 py-4 md:p-6 rounded-3xl bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700"
  >
    <Translatable>
      <div class="px-2.5 no-tailwindcss" v-html="lesson.content" />
    </Translatable>

    <div class="mt-10 flex flex-wrap items-center gap-2.5">
      <FwbButton
        v-if="prevPageUrl"
        @click="router.visit(prevPageUrl)"
        type="button"
        color="alternative"
      >
        Назад
      </FwbButton>

      <FwbButton
        v-if="canComplete"
        @click="complete"
        type="button"
        color="green"
        class="flex items-center"
      >
        Отметить завершенным
        <template #suffix>
          <Icon :icon="['fas', 'check']" size="sm" />
        </template>
      </FwbButton>

      <FwbButton
        v-if="nextPageUrl"
        @click="router.visit(nextPageUrl)"
        type="button"
        class="flex items-center"
      >
        Следующий урок
        <template #suffix>
          <Icon :icon="['fas', 'arrow-right-long']" size="sm" />
        </template>
      </FwbButton>
    </div>
  </article>

  <DiscussionSection :discussion-id="lesson.discussion.id" />
</template>
