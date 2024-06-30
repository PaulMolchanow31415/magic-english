<script setup>
import { FwbButton, FwbInput } from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import Toaster from '@/Shared/Toaster.vue'
import { Toast } from '@/Classes'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import { quickEnableRef } from '@/Helpers'
import TextRedactor from '@/Shared/TextRedactor.vue'
import { Accordion, AccordionItem } from '@/Shared/Accordion'

defineProps({ faqs: Array })

const isShowEditModal = ref(false)
const isShowDeleteModal = ref(false)
const isSaved = ref(false)
const isDeleted = ref(false)
const isError = ref(false)
const form = useForm({ id: null, heading: '', content: '' })

function clearForm() {
  form.id = null
  form.heading = ''
  form.content = ''
}

function saveFaq() {
  form.post(route('admin.faq.store'), {
    onSuccess: () => quickEnableRef(isSaved),
    onError: () => quickEnableRef(isError),
    onFinish: () => {
      isShowEditModal.value = false
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
      isShowDeleteModal.value = false
      clearForm()
    },
    preserveScroll: true,
  })
}

function selectFaq(faq) {
  form.id = faq.id
  form.heading = faq.heading
  form.content = faq.content
}

function handleEdit(faq) {
  selectFaq(faq)
  isShowEditModal.value = true
}

function handleDelete(faq) {
  selectFaq(faq)
  isShowDeleteModal.value = true
}
</script>

<template>
  <Head title="Ответы на частые вопросы" />

  <Toaster
    :toasts="[
      new Toast({ type: 'success', isShow: isSaved, value: 'Ответ на вопрос успешно добавлен' }),
      new Toast({ type: 'success', isShow: isDeleted, value: 'Ответ на вопрос успешно удален!' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <Accordion flush>
    <AccordionItem :order="0" open-first>
      <template #heading class="bg-blue-50 dark:bg-blue-950">Добавить FAQ</template>
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
    </AccordionItem>
    <AccordionItem v-for="(faq, index) in faqs" :order="index + 1">
      <template #heading>{{ faq.heading }}</template>
      <div v-html="faq.content" class="text-gray-500 dark:text-gray-400 accordion-content" />
      <div class="accordion-button-group">
        <button @click="handleDelete(faq)" type="button" class="hover:text-red-600">Удалить</button>
        <button @click="handleEdit(faq)" type="button" class="hover:text-blue-600">
          Редактировать
        </button>
      </div>
    </AccordionItem>
  </Accordion>

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
