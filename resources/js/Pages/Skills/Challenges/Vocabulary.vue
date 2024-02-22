<script>
import { defineComponent, toRaw } from 'vue'
import { FwbButton, FwbHeading, FwbProgress } from 'flowbite-vue'
import AutoHead from '@/Shared/AutoHead.vue'
import Radio from '@/Shared/Radio.vue'
import Tooltip from '@/Shared/Tooltip.vue'
import { useForm } from '@inertiajs/vue3'

const makeAnswer = (obj, isRight) => ({
  ...obj,
  isRightAnswer: isRight,
})

const rememberPositions = new Set()
let i, position
let code

export default defineComponent({
  name: 'Vocabulary',
  components: { Tooltip, FwbHeading, Radio, FwbProgress, AutoHead, FwbButton },
  inject: ['randomInt', 'shuffle'],
  props: { vocabularies: Array },
  setup: () => ({ completed: useForm({ vocabulary_ids: [] }) }),
  data() {
    return {
      rightAnswer: { isRightAnswer: true },
      isDirty: false,
      isSkipped: false,
      picked: null,
      questions: [],
      progress: 0,
    }
  },
  created() {
    this.generateQuestion()
  },
  mounted() {
    document.addEventListener('keydown', this.handleKeyDown)
  },
  unmounted() {
    document.removeEventListener('keydown', this.handleKeyDown)
  },
  computed: {
    availableQuestions() {
      return this.vocabularies.filter(
        (vocabulary) => !this.completed.vocabulary_ids.find((id) => id === vocabulary.id),
      )
    },
    progressColor() {
      if (this.isDirty) {
        return 'red'
      }
      if (this.progress === 100) {
        return 'green'
      }
      if (this.progress > 75) {
        return 'indigo'
      }
      if (this.progress > 50) {
        return 'purple'
      }
      if (this.progress > 25) {
        return 'blue'
      }

      return 'yellow'
    },
  },
  watch: {
    picked(selected) {
      if (this.isSkipped) {
        return
      }

      if (selected && !selected.isRightAnswer) {
        this.isDirty = true
      }
    },
  },
  methods: {
    handleKeyDown(e) {
      code = e.keyCode

      if (code >= 49 && code <= 51) {
        this.picked = this.questions.at(code - 49)
        return
      }

      if (code !== 13) {
        return
      }

      if (this.progress === 100) {
        this.finish()
      } else {
        this.picked?.isRightAnswer ? this.next() : this.skip()
      }
    },
    generateQuestion() {
      rememberPositions.clear()
      position = this.randomInt(0, this.availableQuestions.length - 1)
      i = 1

      this.rightAnswer = makeAnswer(this.availableQuestions[position], true)
      this.questions.length = 0
      rememberPositions.add(this.vocabularies.findIndex((v) => this.rightAnswer.id === v.id))
      this.questions.push(this.rightAnswer)

      while (i < 3) {
        position = this.randomInt(0, this.vocabularies.length - 1)

        if (rememberPositions.has(position)) {
          continue
        }

        rememberPositions.add(position)
        this.questions.push(makeAnswer(toRaw(this.vocabularies[position]), false))
        ++i
      }

      this.shuffle(this.questions)
    },
    selectionStatus(question) {
      if (!this.picked || this.picked?.id !== question.id) {
        return
      }

      return this.picked.isRightAnswer ? 'success' : 'error'
    },
    skip() {
      this.isSkipped = true
      this.picked = this.rightAnswer
    },
    next() {
      if (!this.isDirty && !this.isSkipped && this.picked.isRightAnswer) {
        this.completed.vocabulary_ids.push(this.picked.id)
      }

      this.progress = Math.round(
        (this.completed.vocabulary_ids.length / this.vocabularies.length) * 100,
      )
      this.isDirty = false
      this.isSkipped = false

      if (this.completed.vocabulary_ids.length < this.vocabularies.length) {
        this.generateQuestion()
        this.picked = null
      }
    },
    finish() {
      this.completed.post(route('student.complete-vocabularies'))
    },
  },
})
</script>

<template>
  <AutoHead />

  <section class="">
    <FwbHeading tag="h3" v-text="rightAnswer.en" class="mb-6" />

    <div class="flex gap-6 items-center">
      <img
        v-if="rightAnswer.poster_url"
        :src="rightAnswer.poster_url"
        :alt="rightAnswer.en"
        class="object-cover rounded max-w-full w-32 blur transition"
        :class="{ '!blur-none': picked?.isRightAnswer }"
      />
      <ul v-if="questions.length" class="space-y-4 w-full grow">
        <li v-for="(question, keyIndex) in questions" :key="question.id">
          <Tooltip theme="light" placement="left">
            <template #trigger>
              <Radio
                v-model="picked"
                :value="question"
                :label="question.translations[0]"
                :status="selectionStatus(question)"
                :disabled="picked?.isRightAnswer"
              />
            </template>
            <template #content>{{ keyIndex + 1 }}</template>
          </Tooltip>
        </li>
      </ul>
    </div>

    <Tooltip placement="bottom" theme="light" class="py-6">
      <template #content>
        <span class="leading-loose">Просто нажмите</span>&nbsp;&nbsp;<kbd>Enter</kbd>
      </template>
      <template #trigger>
        <div class="flex justify-center">
          <FwbButton
            v-if="picked?.isRightAnswer && progress !== 100"
            @click="next"
            size="xl"
            color="green"
            type="button"
            class="px-24"
          >
            Дальше
          </FwbButton>
          <FwbButton
            v-if="!picked?.isRightAnswer"
            @click="skip"
            size="xl"
            color="light"
            type="button"
            class="px-24 dark:hover:bg-gray-700"
          >
            Не знаю
          </FwbButton>
          <FwbButton
            v-if="progress === 100"
            @click="finish"
            size="xl"
            color="purple"
            type="button"
            class="px-24 sm:px-32"
          >
            Завершить
          </FwbButton>
        </div>
      </template>
    </Tooltip>

    <FwbProgress
      :color="progressColor"
      size="sm"
      :progress="progress"
      label-progress
      label-position="outside"
      class="absolute bottom-8 container"
    />
  </section>
</template>

<style scoped></style>
