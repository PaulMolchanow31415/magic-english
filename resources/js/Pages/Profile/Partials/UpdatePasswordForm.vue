<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import FormSection from '@/Pages/Auth/Partials/FormSection.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { PrimaryButton } from '@/Shared/Buttons'
import { FwbInput } from 'flowbite-vue'
import { useFlashMessages } from '@/Composables'

const passwordInput = ref(null)
const currentPasswordInput = ref(null)
const { showMessage } = useFlashMessages()

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updatePassword = () => {
  form.put(route('user-password.update'), {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => {
      showMessage('Пароль успешно обновлен', 'success')
      form.reset()
    },
    onError: () => {
      showMessage('Неправильно введен пароль', 'warning')

      if (form.errors.password) {
        form.reset('password', 'password_confirmation')
        passwordInput.value.focus()
      }

      if (form.errors.current_password) {
        form.reset('current_password')
        currentPasswordInput.value.focus()
      }
    },
  })
}
</script>

<template>
  <FormSection @submitted="updatePassword">
    <template #title>Обновление пароля</template>

    <template #description>
      Убедитесь, что ваша учетная запись использует длинный случайный пароль, чтобы оставаться в
      безопасности.
    </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <InputLabel value="Текущий пароль">
          <FwbInput
            ref="currentPasswordInput"
            v-model="form.current_password"
            type="password"
            autocomplete="current-password"
            required
            :validation-status="form.errors.current_password ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.current_password }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <InputLabel value="Новый пароль">
          <FwbInput
            ref="passwordInput"
            v-model="form.password"
            type="password"
            autocomplete="new-password"
            required
            :validation-status="form.errors.password ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.password }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <InputLabel value="Подтверждение пароля">
          <FwbInput
            v-model="form.password_confirmation"
            type="password"
            autocomplete="new-password"
            required
            :validation-status="form.errors.password_confirmation ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.password_confirmation }}</template>
          </FwbInput>
        </InputLabel>
      </div>
    </template>

    <template #actions>
      <PrimaryButton :loading="form.processing">Сохранить</PrimaryButton>
    </template>
  </FormSection>
</template>
