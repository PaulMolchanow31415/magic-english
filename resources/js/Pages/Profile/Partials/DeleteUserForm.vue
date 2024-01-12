<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ActionSection from '@/Components/ActionSection.vue'
import DangerButton from '@/Shared/DangerButton.vue'
import DialogModal from '@/Shared/DialogModal.vue'
import InputError from '@/Shared/InputError.vue'
import SecondaryButton from '@/Shared/SecondaryButton.vue'
import TextInput from '@/Shared/TextInput.vue'

const confirmingUserDeletion = ref(false)
const passwordInput = ref(null)

const form = useForm({
  password: '',
})

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true

  setTimeout(() => passwordInput.value.focus(), 250)
}

const deleteUser = () => {
  form.delete(route('current-user.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  })
}

const closeModal = () => {
  confirmingUserDeletion.value = false

  form.reset()
}
</script>

<template>
  <ActionSection>
    <template #title>Удалить учетную запись</template>

    <template #description>Безвозвратно удаляет учетную запись.</template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        Как только ваша учетная запись будет удалена, все ее ресурсы и данные будут удалены
        безвозвратно. Перед удалением вашей учетной записи, пожалуйста, загрузите любые данные или
        информацию, которые вы хотите сохранить.
      </div>

      <div class="mt-5">
        <DangerButton @click="confirmUserDeletion">Удалить</DangerButton>
      </div>

      <!-- Delete Account Confirmation Modal -->
      <DialogModal :show="confirmingUserDeletion" @close="closeModal">
        <template #title>Удалить учетную запись</template>

        <template #content>
          Вы уверены, что хотите удалить свою учетную запись? Как только ваша учетная запись будет
          удалена, все ее ресурсы и данные будут удалены безвозвратно. Пожалуйста, введите свой
          пароль для подтверждения, что вы хотели бы безвозвратно удалить свою учетную запись.

          <div class="mt-4">
            <TextInput
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="mt-1 block w-3/4"
              placeholder="Пароль"
              autocomplete="current-password"
              @keyup.enter="deleteUser"
            />

            <InputError :message="form.errors.password" class="mt-2" />
          </div>
        </template>

        <template #footer>
          <SecondaryButton @click="closeModal">Закрыть</SecondaryButton>

          <DangerButton class="ms-3" :processing="form.processing" @click="deleteUser">
            Удалить
          </DangerButton>
        </template>
      </DialogModal>
    </template>
  </ActionSection>
</template>
