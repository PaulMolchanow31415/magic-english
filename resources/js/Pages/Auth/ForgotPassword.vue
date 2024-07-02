<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Pages/Auth/Partials/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Pages/Auth/Partials/AuthenticationCardLogo.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { PrimaryButton } from '@/Shared/Buttons'
import StubLayout from '@/Layouts/StubLayout.vue'
import { FwbInput } from 'flowbite-vue'
import { useReCaptcha } from 'vue-recaptcha-v3'

defineOptions({ layout: StubLayout })

defineProps({
  status: String,
})

const form = useForm({
  email: '',
  recaptcha_token: null,
})

const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()

async function submit() {
  await recaptchaLoaded()

  form.recaptcha_token = await executeRecaptcha('forgotPassword')

  form.post(route('password.email'))
}
</script>

<template>
  <Head title="Восстановление пароля" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      <span class="dark:text-white block mb-1 prose-xl">Забыли свой пароль?</span>
      Без проблем. Просто сообщите нам свой адрес электронной почты, и мы вышлем вам по электронной
      почте ссылку для сброса пароля, которая позволит вам выбрать новый.
    </p>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel value="Email">
          <FwbInput
            v-model="form.email"
            type="email"
            required
            autofocus
            autocomplete="username"
            :validation-status="form.errors.email ? 'error' : ''"
          >
            <template #validationMessage>
              {{ form.errors.email }}
            </template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :loading="form.processing"> Получить ссылку на сброс пароля </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
