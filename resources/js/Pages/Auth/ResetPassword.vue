<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import StubLayout from '@/Layouts/StubLayout.vue'
import { FwbInput } from 'flowbite-vue'

defineOptions({ layout: StubLayout })

const props = defineProps({
  email: String,
  token: String,
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

async function submit() {
  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Сброс пароля" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <template #heading>Сброс пароля</template>

    <form @submit.prevent="submit">
      <div>
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
        <InputLabel value="Новый пароль">
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
      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :processing="form.processing">Сбросить пароль</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
