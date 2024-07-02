<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Pages/Auth/Partials/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Pages/Auth/Partials/AuthenticationCardLogo.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { PrimaryButton } from '@/Shared/Buttons'
import { FwbInput } from 'flowbite-vue'

const form = useForm({
  password: '',
})

const passwordInput = ref(null)

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => {
      form.reset()

      passwordInput.value.focus()
    },
  })
}
</script>

<template>
  <Head title="Подтверждение пароля" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      Это защищенная область приложения. Пожалуйста, подтвердите свой пароль, прежде чем продолжить.
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel value="Пароль">
          <FwbInput
            v-model="form.password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            autofocus
            :validation-status="form.errors.password ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.password }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="flex justify-end mt-4">
        <PrimaryButton class="ms-4" :loading="form.processing">Подтвердить</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
