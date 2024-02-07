<script setup>
import {
  FwbAccordion,
  FwbAccordionContent,
  FwbAccordionHeader,
  FwbAccordionPanel,
  FwbButton,
  FwbHeading,
  FwbInput,
  FwbModal,
} from 'flowbite-vue'
import PhoneticsEditor from '@/Pages/Admin/Partials/PhoneticsEditor.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import { router, useForm } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { set } from '@vueuse/core'
import Sorter from '@/Shared/Sorter.vue'
import SortableItem from '@/Classes/SortableItem.js'

const props = defineProps({
  courseId: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['close'])

const editor = ref(null)
const grammarRules = ref([])

const sortableGrammars = computed(() =>
  grammarRules.value
    .sort((a, b) => a.order - b.order)
    .map((r) => new SortableItem({ id: r.id, name: r.title })),
)

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

function handleEdit(grammar) {
  form.id = grammar.id
  form.course_id = grammar.course_id
  form.title = grammar.title
  form.content = grammar.content
  form.phonetics = grammar.phonetics
  editor.value.scrollIntoView({ behavior: 'smooth' })
}

function loadGrammars() {
  axios
    .get(route('admin.grammar.show', { courseId: props.courseId }))
    .then((res) => set(grammarRules, res.data))
}

function handleSuccess() {
  form.reset()
  loadGrammars()
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

function remove(grammar) {
  router.delete(route('admin.grammar.destroy', { id: grammar.id }), {
    onSuccess: handleSuccess,
    preserveScroll: true,
    preserveState: true,
  })
}

watch(
  () => props.courseId,
  (value) => {
    if (value) {
      loadGrammars()
    }
  },
)
</script>

<template>
  <FwbModal size="4xl" v-show="Boolean(courseId)" @close="handleClose" @click:outside="handleClose">
    <template #header>
      <h3 class="pl-2 text-lg font-semibold text-gray-900 dark:text-white">
        Редактирование грамматики
      </h3>
    </template>
    <template #body>
      <FwbAccordion flush open-first-item always-open>
        <FwbAccordionPanel>
          <FwbAccordionHeader>Добавление грамматики</FwbAccordionHeader>
          <FwbAccordionContent>
            <form ref="editor" @submit.prevent="confirmUpdate" method="post">
              <div class="mb-4">
                <InputLabel value="Заголовок">
                  <FwbInput
                    v-model="form.title"
                    placeholder="Названике заголовка"
                    :validation-status="form.errors.title ? 'error' : ''"
                  >
                    <template #validationMessage>
                      {{ form.errors.title }}
                    </template>
                  </FwbInput>
                </InputLabel>
              </div>
              <TextRedactor
                v-model="form.content"
                toolbar-style="full"
                placeholder="Грамматика"
                :error-message="form.errors.content"
              />
              <PhoneticsEditor
                class="my-6"
                v-model="form.phonetics"
                :error-message="form.errors.phonetics"
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
          </FwbAccordionContent>
        </FwbAccordionPanel>

        <FwbAccordionPanel v-for="grammar in grammarRules" :key="grammar.id">
          <FwbAccordionHeader>{{ grammar.title }}</FwbAccordionHeader>
          <FwbAccordionContent>
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
          </FwbAccordionContent>
        </FwbAccordionPanel>
      </FwbAccordion>

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
</template>

<style scoped></style>