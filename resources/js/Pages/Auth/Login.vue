<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Shared/InputError.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { useChallengeV3 } from 'vue-recaptcha'
import StubLayout from '@/Layouts/StubLayout.vue'
import { FwbCheckbox, FwbInput } from 'flowbite-vue'
import PrimaryButton from '@/Shared/PrimaryButton.vue'

defineOptions({ layout: StubLayout })

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
  recaptcha_token: null,
})

const { execute } = useChallengeV3('login')

async function submit() {
  form.recaptcha_token = await execute()

  form
    .transform((data) => ({ ...data, remember: form.remember ? 'on' : '' }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
    })
}
</script>

<template>
  <Head title="Вход" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <template #heading>Вход</template>

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

      <div class="mt-4">
        <InputLabel value="Пароль">
          <FwbInput
            v-model="form.password"
            type="password"
            required
            autocomplete="current-password"
          />
        </InputLabel>

        <InputError class="mt-2" :message="form.errors.recaptcha_token" />
      </div>

      <div class="mt-4">
        <FwbCheckbox
          v-model="form.remember"
          name="remember"
          label="Запомнить меня"
          class="select-none cursor-pointer"
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          Забыли пароль?
        </Link>

        <PrimaryButton class="ms-4" :processing="form.processing">Войти</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
