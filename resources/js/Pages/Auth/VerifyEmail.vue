<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  status: String,
})

const form = useForm({})

const submit = () => {
  form.post(route('verification.send'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
  <Head title="Проверка по электронной почте" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      Прежде чем продолжить, не могли бы вы подтвердить свой адрес электронной почты, перейдя по
      ссылке, которую мы только что отправили вам по электронной почте? Если вы не получили
      электронное письмо, мы с радостью отправим вам другое.
    </div>

    <div
      v-if="verificationLinkSent"
      class="mb-4 font-medium text-sm text-green-600 dark:text-green-400"
    >
      На адрес электронной почты, который вы указали в своем профиле, была отправлена новая ссылка
      для подтверждения настройки.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <PrimaryButton :processing="form.processing">
          Повторно отправьте электронное письмо с подтверждением
        </PrimaryButton>

        <div>
          <Link
            :href="route('profile.show')"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
          >
            Редактировать профиль
          </Link>

          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 ms-2"
          >
            Выйти
          </Link>
        </div>
      </div>
    </form>
  </AuthenticationCard>
</template>
