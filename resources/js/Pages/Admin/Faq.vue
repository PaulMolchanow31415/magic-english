<script setup>
import { FwbButton, FwbInput } from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import { Accordion, AccordionItem } from '@/Shared/Accordion'
import { useFlashMessages } from '@/Composables'

defineProps({ faqs: Array })

const isShowEditModal = ref(false)
const isShowDeleteModal = ref(false)
const form = useForm({ id: null, heading: '', content: '' })
const { showMessage } = useFlashMessages({ closable: true })

function clearForm() {
  form.id = null
  form.heading = ''
  form.content = ''
}

function saveFaq() {
  form.post(route('admin.faq.store'), {
    onSuccess: () => showMessage('Ответ на вопрос успешно добавлен', 'success'),
    onError: () => showMessage('Ошибка, не удалось добавть ответ на вопрос', 'warning'),
    onFinish: () => {
      isShowEditModal.value = false
      clearForm()
    },
    preserveScroll: true,
  })
}

function deleteFaq() {
  form.delete(route('admin.faq.destroy', { id: form.id }), {
    onSuccess: () => showMessage('Ответ на вопрос успешно удален!', 'success'),
    onError: () => showMessage('Ошибка', 'warning'),
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

  <Accordion flush open-first>
    <AccordionItem>
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
          :error="form.errors.content"
        />
        <FwbButton type="submit" class="mt-4 flex flex-nowrap" :loading="form.processing">
          Сохранить
        </FwbButton>
      </form>
    </AccordionItem>
    <AccordionItem v-for="faq in faqs">
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
      :error="form.errors.content"
    />
  </UpdateModal>
</template>
