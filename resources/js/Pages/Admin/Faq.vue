<script setup>
import {
  FwbAccordion,
  FwbAccordionContent,
  FwbAccordionHeader,
  FwbAccordionPanel,
  FwbButton,
  FwbInput,
} from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Types/Toast.js'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import { set } from '@vueuse/core'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import { quickEnableRef } from '@/Helpers/quickEnableRef.ts'
import TextRedactor from '@/Shared/TextRedactor.vue'

defineProps({ faqs: Array })

const isShowEditModal = ref(false)
const isShowDeleteModal = ref(false)
const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const form = useForm({ id: null, heading: '', content: '' })

function saveFaq() {
  form.post(route('admin.faq.store'), {
    onSuccess: () => quickEnableRef(isSaved),
    onError: () => quickEnableRef(isError),
    onFinish: () => {
      set(isShowEditModal, false)
      clearForm()
    },
    preserveScroll: true,
  })
}

function deleteFaq() {
  form.delete(route('admin.faq.destroy', { id: form.id }), {
    onSuccess: () => quickEnableRef(isDeleted),
    onError: () => quickEnableRef(isError),
    onFinish: () => {
      set(isShowDeleteModal, false)
      clearForm()
    },
    preserveScroll: true,
  })
}

function clearForm() {
  form.id = null
  form.heading = ''
  form.content = ''
}

function selectFaq(faq) {
  form.id = faq.id
  form.heading = faq.heading
  form.content = faq.content
}

function handleEdit(faq) {
  selectFaq(faq)
  set(isShowEditModal, true)
}

function handleDelete(faq) {
  selectFaq(faq)
  set(isShowDeleteModal, true)
}
</script>

<template>
  <Head title="Ответы на частые вопросы" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Ответ на вопрос успешно добавлен' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Ответ на вопрос успешно удален!' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <FwbAccordion flush>
    <FwbAccordionPanel>
      <FwbAccordionHeader class="bg-blue-50 dark:bg-blue-950">Добавить FAQ</FwbAccordionHeader>
      <FwbAccordionContent>
        <form @submit.prevent="saveFaq" method="post">
          <div class="mb-4">
            <InputLabel value="Заголовок">
              <FwbInput
                v-model="form.heading"
                placeholder="Вопрос"
                :validation-status="form.errors.heading ? 'error' : ''"
              >
                <template #validationMessage>{{ form.errors.heading }}</template>
              </FwbInput>
            </InputLabel>
          </div>
          <TextRedactor
            v-model="form.content"
            toolbar-style="minimal"
            placeholder="Ответ на вопрос"
            :error-message="form.errors.content"
          />
          <FwbButton type="submit" class="mt-4 flex flex-nowrap" :loading="form.processing">
            Сохранить
          </FwbButton>
        </form>
      </FwbAccordionContent>
    </FwbAccordionPanel>

    <FwbAccordionPanel v-for="faq in faqs">
      <FwbAccordionHeader>{{ faq.heading }}</FwbAccordionHeader>
      <FwbAccordionContent>
        <div v-html="faq.content" class="text-gray-500 dark:text-gray-400 accordion-content" />
        <div class="accordion-button-group">
          <button @click="handleDelete(faq)" type="button" class="hover:text-red-600">
            Удалить
          </button>
          <button @click="handleEdit(faq)" type="button" class="hover:text-blue-600">
            Редактировать
          </button>
        </div>
      </FwbAccordionContent>
    </FwbAccordionPanel>
  </FwbAccordion>

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="isShowDeleteModal"
    @confirm="deleteFaq"
    @close="isShowDeleteModal = false"
  >
    <template #message> Вы уверены, что хотите удалить ответ на вопрос? </template>
  </DeleteConfirmationModal>

  <!-- Edit modal -->
  <UpdateModal
    title="Обновление пользователя"
    :show="isShowEditModal"
    :loading="form.processing"
    @confirm="saveFaq"
    @close="isShowEditModal = false"
  >
    <div class="mb-4">
      <InputLabel value="Заголовок">
        <FwbInput
          v-model="form.heading"
          placeholder="Вопрос"
          :validation-status="form.errors.heading ? 'error' : ''"
        >
          <template #validationMessage>{{ form.errors.heading }}</template>
        </FwbInput>
      </InputLabel>
    </div>
    <TextRedactor
      v-model="form.content"
      placeholder="Ответ на вопрос"
      toolbar-style="minimal"
      :error-message="form.errors.content"
    />
  </UpdateModal>
</template>
