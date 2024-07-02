<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Pages/Auth/Partials/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Pages/Auth/Partials/AuthenticationCardLogo.vue'
import InputError from '@/Shared/InputError.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import StubLayout from '@/Layouts/StubLayout.vue'
import { FwbCheckbox, FwbInput } from 'flowbite-vue'
import PrimaryButton from '@/Shared/PrimaryButton.vue'
import { useReCaptcha } from 'vue-recaptcha-v3'

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

const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()

async function submit() {
  await recaptchaLoaded()

  form.recaptcha_token = await executeRecaptcha('login')

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

      <div class="mt-6 flex justify-between gap-2 items-center">
        <Link
          class="px-2 py-2 inline-flex items-center bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
          :href="route('oauth2.google.redirect')"
        >
          <span
            style="
              display: block;
              width: 1.2rem;
              height: 1.2rem;
              background-size: 1.2rem;
              background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAgVBMVEX7+/v////+/v78/Pz8/Pz////z7er+/v71o5zwdGrsV0rqRDbziXL3trHzlIzuZlr+9t77vAXxdyD934f3oRv80FD7whxChfStyvr93H2fwfnkwi7d2uadsyo1qFOo2rVOs2lOju9soPeOy5FlvX3Q69hRoLm44cOX0qk2o2x0w4kKGCk5AAAABnRSTlMBWIrL6yVECYGgAAABPklEQVQ4y41T7VaDMAyF0g+gFkSdzukK22Bje/8HNE1baAc67x/O4d4kN2mSJDNSklHOaUbSZAWMcDGBE3bPExGDkzg8EwtkQRJGbVRelFKWRW6L0TueP8kJBUqoz4H5lYygsErgL495WWEVdMqC+LpSqqonXnDmEnCkS2V/q7LyrZgUxg/6q/myV+gkhc9z8wLxK7wQKVZ4bZo3qdZ4qGF63DSgWOWhUzOkdxBs3J+PGVs0YSoD33wuBTvT32PBwxLe5Ffk7dsLMt/mXrehYGsEHNu0g9pr3YWDOgB/tINClycN6GfFcbbgHssIdDdYejgb/uAfi5nAi7aSU9uOvdZXlwCf2y5MqyNcz7tpYdzKXWLFGKycX9rbTPdDtLRJ4ta+vXXgox+tWcr+fzjGKf/z9PB4abCKy+P99fx/AF6GJzxJVZRIAAAAAElFTkSuQmCC');
            "
            title="Войти с Google"
          >
            <i class="sr-only">Войти с Google</i>
          </span>
        </Link>

        <div class="flex items-center justify-end">
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
          >
            Забыли пароль?
          </Link>

          <PrimaryButton class="ms-4" :processing="form.processing">Войти</PrimaryButton>
        </div>
      </div>
    </form>
  </AuthenticationCard>
</template>
