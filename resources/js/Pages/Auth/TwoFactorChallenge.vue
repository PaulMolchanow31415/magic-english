<script setup>
import { nextTick, ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Pages/Auth/Partials/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Pages/Auth/Partials/AuthenticationCardLogo.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { PrimaryButton } from '@/Shared/Buttons'
import { FwbA, FwbInput } from 'flowbite-vue'
import StubLayout from '@/Layouts/StubLayout.vue'

defineOptions({ layout: StubLayout })

const recovery = ref(false)

const form = useForm({
  code: '',
  recovery_code: '',
})

const recoveryCodeInput = ref(null)
const codeInput = ref(null)

const toggleRecovery = async () => {
  recovery.value ^= true

  await nextTick()

  if (recovery.value) {
    recoveryCodeInput.value.focus()
    form.code = ''
  } else {
    codeInput.value.focus()
    form.recovery_code = ''
  }
}

const submit = () => {
  form.post(route('two-factor.login'))
}
</script>

<template>
  <Head title="Двухфакторная аутентификация" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      <template v-if="!recovery">
        Пожалуйста, подтвердите доступ к своей учетной записи, введя код аутентификации,
        предоставленный вашим приложением для проверки подлинности или
        <FwbA href="mailto:pmolch3.1415@gmail.com" target="_blank" class="font-semibold">
          обратитесь к нам по email
        </FwbA>
      </template>

      <template v-else>
        Пожалуйста, подтвердите доступ к своей учетной записи, введя один из ваших кодов экстренного
        восстановления.
      </template>
    </div>

    <form @submit.prevent="submit">
      <div v-if="!recovery">
        <InputLabel value="Код">
          <FwbInput
            id="code"
            ref="codeInput"
            v-model="form.code"
            type="text"
            inputmode="numeric"
            autofocus
            autocomplete="one-time-code"
            :validation-status="form.errors.code ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.code }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <div v-else>
        <InputLabel value="Код восстановления">
          <FwbInput
            ref="recoveryCodeInput"
            v-model="form.recovery_code"
            autocomplete="one-time-code"
            :validation-status="form.errors.recovery_code ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.recovery_code }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <div class="flex items-center justify-end mt-4">
        <button
          type="button"
          class="text-sm text-gray-600 dark:text-gray-400 dark:hover:text-white hover:text-gray-900 underline cursor-pointer"
          @click.prevent="toggleRecovery"
        >
          <template v-if="!recovery">Используйте код восстановления</template>

          <template v-else>Используйте код аутентификации</template>
        </button>

        <PrimaryButton class="ms-4" :loading="form.processing">Вход</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
