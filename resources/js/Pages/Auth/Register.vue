<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import { useChallengeV3 } from 'vue-recaptcha'
import StubLayout from '@/Layouts/StubLayout.vue'
import { FwbInput } from 'flowbite-vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineOptions({ layout: StubLayout })

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
  recaptcha_token: null,
})

const { execute } = useChallengeV3('register')

async function submit() {
  form.recaptcha_token = await execute()

  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Регистрация" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <template #heading>Регистрация</template>

    <form @submit.prevent="submit" class="space-y-6">
      <div>
        <InputLabel value="Имя">
          <FwbInput
            v-model="form.name"
            type="text"
            name="name"
            required
            autofocus
            autocomplete="name"
            :validation-status="form.errors.name ? 'error' : ''"
          >
            <template #validationMessage>
              {{ form.errors.name }}
            </template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="mt-4">
        <InputLabel value="Email">
          <FwbInput
            v-model="form.email"
            type="email"
            name="email"
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
            name="password"
            required
            autocomplete="new-password"
            :validation-status="form.errors.password ? 'error' : ''"
          >
            <template #helper>минимум 8 символов</template>
            <template #validationMessage>{{ form.errors.password }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="mt-4">
        <InputLabel value="Подтверждение пароля">
          <FwbInput
            v-model="form.password_confirmation"
            type="password"
            name="password"
            autocomplete="new-password"
            required
            :validation-status="form.errors.password_confirmation ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.password_confirmation }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <InputError class="mt-2" :message="form.errors.recaptcha_token" />

      <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
        <InputLabel for="terms">
          <div class="flex items-center">
            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

            <div class="ms-2">
              Я принимаю
              <a
                target="_blank"
                :href="route('terms.show')"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >условия обслуживания</a
              >
              и
              <a
                target="_blank"
                :href="route('policy.show')"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >политику конфиденциальности</a
              >
            </div>
          </div>
          <InputError class="mt-2" :message="form.errors.terms" />
        </InputLabel>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          Уже зарегистрированы?
        </Link>

        <PrimaryButton class="ms-4" :processing="form.processing">Регистрация</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
