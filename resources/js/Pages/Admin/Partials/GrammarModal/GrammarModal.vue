<script setup lang="ts">
import { FwbButton, FwbHeading, FwbInput, FwbModal } from 'flowbite-vue'
import PhoneticsEditor from '../PhoneticsEditor.vue'
import InputLabel from '../../../../Shared/InputLabel.vue'
import TextRedactor from '../../../../Shared/TextRedactor.vue'
import { router, useForm } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import Sorter from '../../../../Widgets/Sorter.vue'
import { SortableItem } from '../../../../Entities'
import Opacity300Transition from '../../../../Shared/Animations/Opacity300Transition.vue'
import { Accordion, AccordionItem } from '../../../../Shared/Accordion'
import { loadGrammars } from './api'
import { GrammarRule } from './types'

const props = defineProps({
  courseId: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['close'])

const editor = ref<HTMLFormElement>(null)
const grammarRules = ref<GrammarRule[]>([])

const sortableGrammars = computed(() => {
  return grammarRules.value
    .sort((a, b) => a.order - b.order)
    .map((rule) => new SortableItem({ id: rule.id, name: rule.title }))
})

const form = useForm({
  id: null,
  course_id: null,
  title: '',
  content: '',
  order: 0,
  phonetics: [],
})

const grammarOrdersForm = useForm({ items: [] })

function handleClose() {
  grammarOrdersForm.reset()
  form.reset()
  emit('close')
}

function handleEdit(grammar: GrammarRule) {
  form.id = grammar.id
  form.course_id = grammar.course_id
  form.title = grammar.title
  form.content = grammar.content
  form.phonetics = grammar.phonetics
  editor.value.scrollIntoView({ behavior: 'smooth' })
}

async function handleSuccess() {
  form.reset()
  grammarRules.value = await loadGrammars(props.courseId)
}

function confirmUpdate() {
  form
    .transform((data) => ({
      ...data,
      course_id: props.courseId,
      order:
        grammarRules.value.length > 0
          ? data.order || Math.max(...grammarRules.value.map((r) => r.order)) + 1
          : 1,
    }))
    .post(route('admin.grammar.store'), {
      onSuccess: handleSuccess,
      preserveScroll: true,
    })
}

function handleChangeOrder() {
  grammarOrdersForm
    .transform((data) => ({
      ...data,
      items: data.items.map((grammar, index) => ({ id: grammar.id, order: index + 1 })),
    }))
    .put(route('admin.grammar.change-order'), {
      onSuccess: handleSuccess,
      preserveScroll: true,
    })
}

function remove(grammar: { id: number }) {
  router.delete(route('admin.grammar.destroy', { id: grammar.id }), {
    onSuccess: handleSuccess,
    preserveScroll: true,
    preserveState: true,
  })
}

// prettier-ignore
watch(() => props.courseId, async (value) => {
  value && (grammarRules.value = await loadGrammars(props.courseId))
})
</script>

<template>
  <Opacity300Transition>
    <FwbModal
      size="4xl"
      v-show="Boolean(courseId)"
      @close="handleClose"
      @click:outside="handleClose"
    >
      <template #header>
        <h3 class="pl-2 text-lg font-semibold text-gray-900 dark:text-white">
          Редактирование грамматики
        </h3>
      </template>
      <template #body>
        <Accordion flush always-open>
          <AccordionItem :order="0" open-first>
            <template #heading>Добавление грамматики</template>
            <form ref="editor" @submit.prevent="confirmUpdate" method="post">
              <div class="mb-4">
                <InputLabel value="Заголовок">
                  <FwbInput
                    v-model="form.title"
                    placeholder="Названике заголовка"
                    :validation-status="form.errors.title ? 'error' : null"
                  >
                    <template #validationMessage>{{ form.errors.title }}</template>
                  </FwbInput>
                </InputLabel>
              </div>
              <TextRedactor
                v-model="form.content"
                toolbar-style="full"
                placeholder="Грамматика"
                :error="form.errors.content"
              />
              <PhoneticsEditor
                class="my-6"
                v-model="form.phonetics"
                :error="form.errors.phonetics"
              />
              <FwbButton
                type="submit"
                color="green"
                pill
                class="mt-4 flex flex-nowrap"
                :loading="form.processing"
              >
                Сохранить
              </FwbButton>
            </form>
          </AccordionItem>
          <AccordionItem
            v-for="(grammar, index) in grammarRules"
            :order="index + 1"
            :key="grammar.id"
          >
            <template #heading>{{ grammar.title }}</template>

            <article class="no-tailwindcss" v-html="grammar.content" />

            <ul class="my-2 list-disc list-inside space-y-1.5">
              <li v-for="phonetic in grammar.phonetics" :key="grammar.source">
                <span class="italic dark:text-white" v-text="phonetic.source" />
                —
                {{ phonetic.translation }}
              </li>
            </ul>
            <div class="accordion-button-group">
              <button @click="remove(grammar)" type="button" class="hover:text-red-600">
                Удалить
              </button>
              <button @click="handleEdit(grammar)" type="button" class="hover:text-blue-600">
                Редактировать
              </button>
            </div>
          </AccordionItem>
        </Accordion>

        <form
          @submit.prevent="handleChangeOrder"
          v-if="sortableGrammars.length > 0"
          method="post"
          class="px-4 mt-4"
        >
          <FwbHeading tag="h6">Изменение порядка</FwbHeading>
          <Sorter
            class="mt-4"
            :list="sortableGrammars"
            @change-order="grammarOrdersForm.items = $event"
          />
          <FwbButton
            type="submit"
            color="alternative"
            pill
            class="mt-4 flex flex-nowrap ms-2"
            :loading="grammarOrdersForm.processing"
          >
            Изменить
          </FwbButton>
        </form>
      </template>
      <template #footer>
        <div class="flex items-center gap-4">
          <FwbButton type="button" @click="handleClose" color="alternative"> Закрыть </FwbButton>
        </div>
      </template>
    </FwbModal>
  </Opacity300Transition>
</template>

<style scoped></style>
