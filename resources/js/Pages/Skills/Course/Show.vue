<script setup>
import AutoHead from '@/Shared/AutoHead.vue'
import DiscussionSection from '@/Shared/DiscussionSection.vue'
import { FwbButton, FwbHeading, FwbP, FwbProgress } from 'flowbite-vue'
import { computed, ref, watch } from 'vue'
import Tooltip from '@/Shared/Tooltip.vue'
import Translatable from '@/Shared/Translatable.vue'
import { onKeyStroke } from '@vueuse/core'
import useSay from '@/Composables/useSay.js'
import { router } from '@inertiajs/vue3'

const props = defineProps({ course: Object })

const { isSupported, stop, isPlaying, say } = useSay()

const article = ref(null)
const currentStep = ref(0)

const grammars = computed(() => props.course.grammar_rules.sort((a, b) => a.order - b.order))
const grammar = computed(() => grammars.value.at(currentStep.value))
const hasNext = computed(() => grammars.value.length - 1 > currentStep.value)
const hasPrev = computed(() => currentStep.value > 0)
const progress = computed(() => Math.round((currentStep.value / (grammars.value.length - 1)) * 100))

watch(currentStep, () => article.value.scrollIntoView({ behavior: 'smooth' }))

onKeyStroke('Enter', () => hasNext.value && currentStep.value++)
</script>

<template>
  <AutoHead />

  <article
    ref="article"
    class="container px-6 py-6 rounded-3xl bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700"
  >
    <div>
      <FwbProgress size="sm" :progress="progress" />
      <FwbP class="flex justify-end mt-0.5"> {{ currentStep + 1 }} из {{ grammars.length }} </FwbP>
    </div>

    <Translatable>
      <FwbHeading tag="h2" class="mt-6 mb-4" v-text="grammar.title" />

      <div class="px-2.5 text-lg no-tailwindcss" v-html="grammar.content" />
    </Translatable>

    <blockquote class="blockquote-bordered">
      <ul class="space-y-4">
        <li v-for="phonetic in grammar.phonetics" class="flex gap-2.5">
          <FwbButton
            v-if="isSupported"
            @click="isPlaying ? stop() : say(phonetic.source)"
            pill
            square
            color="alternative"
            type="button"
            class="w-7 h-7 p-0 flex-shrink-0"
          >
            <span class="sr-only">Озвучить</span>
            <Icon :icon="['fas', 'volume-high']" size="xs" />
          </FwbButton>
          {{ phonetic.source }} — {{ phonetic.translation }}
        </li>
      </ul>
    </blockquote>

    <div class="mt-10 flex items-center gap-2.5">
      <FwbButton v-if="hasPrev" @click="currentStep--" size="lg" type="button" color="alternative">
        Назад
      </FwbButton>
      <Tooltip v-if="hasNext" placement="bottom" theme="light">
        <template #trigger>
          <FwbButton @click="currentStep++" size="lg" type="button" class="flex items-center">
            Далее
            <template #suffix>
              <Icon :icon="['fas', 'arrow-right-long']" size="sm" />
            </template>
          </FwbButton>
        </template>
        <template #content>
          <span class="leading-loose">Нажмите</span>&nbsp;&nbsp;<kbd>Enter</kbd>
        </template>
      </Tooltip>
      <FwbButton
        v-else
        @click="router.post(route('student.add-course', { id: props.course.id }))"
        size="lg"
        type="button"
        color="green"
        class="flex items-center"
      >
        Завершить
        <template #suffix>
          <Icon :icon="['fas', 'check']" size="sm" />
        </template>
      </FwbButton>
    </div>
  </article>

  <DiscussionSection :discussionable-id="course.id" />
</template>

<style scoped></style>