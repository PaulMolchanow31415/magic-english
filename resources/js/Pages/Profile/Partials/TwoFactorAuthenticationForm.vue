<script setup>
import { computed, ref, watch } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import ActionSection from '@/Pages/Auth/Partials/ActionSection.vue'
import { ConfirmsPassword } from '@/Pages/Auth/Partials/ConfirmsPassword'
import { DangerButton, PrimaryButton, SecondaryButton } from '@/Shared/Buttons'
import InputLabel from '@/Shared/InputLabel.vue'
import { FwbA, FwbInput } from 'flowbite-vue'
import { getQrCode, getRecoveryCodes, getSecretKey, postRegenerateRecoveryCodes } from './api'

const props = defineProps({ requiresConfirmation: Boolean })

const page = usePage()
const enabling = ref(false)
const confirming = ref(false)
const disabling = ref(false)
const qrCode = ref(null)
const setupKey = ref(null)
const recoveryCodes = ref([])

const form = useForm({ code: '' })

const twoFactorEnabled = computed(() => !enabling.value && page.props.auth.user?.two_factor_enabled)

const showQrCode = async () => {
  let response = await getQrCode()
  qrCode.value = response.data.svg
}

const showSetupKey = async () => {
  let response = await getSecretKey()
  setupKey.value = response.data.secretKey
}

const showRecoveryCodes = async () => {
  let response = await getRecoveryCodes()
  recoveryCodes.value = response.data
}

const enableTwoFactorAuthentication = () => {
  enabling.value = true

  // prettier-ignore
  router.post(route('two-factor.enable'), {}, {
      preserveScroll: true,
      onSuccess: () => Promise.all([showQrCode(), showSetupKey(), showRecoveryCodes()]),
      onFinish: () => {
        enabling.value = false
        confirming.value = props.requiresConfirmation
      },
    },
  )
}

const confirmTwoFactorAuthentication = () => {
  form.post(route('two-factor.confirm'), {
    errorBag: 'confirmTwoFactorAuthentication',
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      confirming.value = false
      qrCode.value = null
      setupKey.value = null
    },
  })
}

const regenerateRecoveryCodes = () => {
  postRegenerateRecoveryCodes().then(() => showRecoveryCodes())
}

const disableTwoFactorAuthentication = () => {
  disabling.value = true

  router.delete(route('two-factor.disable'), {
    preserveScroll: true,
    onSuccess: () => {
      disabling.value = false
      confirming.value = false
    },
  })
}

watch(twoFactorEnabled, (isEnabled) => {
  if (!isEnabled) {
    form.reset()
    form.clearErrors()
  }
})
</script>

<template>
  <ActionSection>
    <template #title>Двухфакторная аутентификация</template>

    <template #description>
      Добавьте дополнительный уровень безопасности своей учетной записи, используя двухфакторную
      аутентификацию.
    </template>

    <template #content>
      <h3
        v-if="twoFactorEnabled && !confirming"
        class="text-lg font-medium text-gray-900 dark:text-gray-100"
      >
        Вы включили двухфакторную аутентификацию.
      </h3>

      <h3
        v-else-if="twoFactorEnabled && confirming"
        class="text-lg font-medium text-gray-900 dark:text-gray-100"
      >
        Завершите включение двухфакторной аутентификации.
      </h3>

      <h3 v-else class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Вы не включили двухфакторную аутентификацию.
      </h3>

      <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
        <p>
          Когда включена двухфакторная аутентификация, вам будет предложено ввести безопасный
          случайный токен во время аутентификации. Вы можете получить этот токен с помощью
          приложения
          <FwbA class="font-semibold" href="https://getaegis.app/" target="_blank">
            Aegis Authenticator
          </FwbA>
          установленного на вашем смартфоне
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p v-if="confirming" class="font-semibold">
              Чтобы завершить включение двухфакторной аутентификации, отсканируйте следующий QR-код
              с помощью приложения для проверки подлинности вашего телефона или введите ключ
              настройки и предоставьте сгенерированный OTP код.
            </p>

            <p v-else>
              Теперь включена двухфакторная аутентификация. Отсканируйте следующий QR-код с помощью
              приложения для проверки подлинности вашего телефона или введите ключ настройки.
            </p>
          </div>

          <div class="mt-4 p-2 inline-block bg-white" v-html="qrCode" />

          <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p class="font-semibold">Установочный ключ: <span v-html="setupKey"></span></p>
          </div>

          <div v-if="confirming" class="mt-4">
            <InputLabel value="Код">
              <FwbInput
                v-model="form.code"
                type="text"
                name="code"
                class="block mt-1 w-1/2"
                inputmode="numeric"
                autofocus
                autocomplete="one-time-code"
                @keyup.enter="confirmTwoFactorAuthentication"
                :validation-status="form.errors.code ? 'error' : ''"
              >
                <template #validationMessage>{{ form.errors.code }}</template>
              </FwbInput>
            </InputLabel>
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0 && !confirming">
          <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p class="font-semibold">
              Сохраните эти коды восстановления в безопасном менеджере паролей. Они могут быть
              использованы для восстановления доступа к вашей учетной записи в случае утери
              устройства двухфакторной аутентификации.
            </p>
          </div>

          <div
            class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-gray-900 dark:text-gray-100 rounded-lg"
          >
            <div v-for="code in recoveryCodes" :key="code">{{ code }}</div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <div v-if="!twoFactorEnabled">
          <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
            <PrimaryButton type="button" :loading="enabling">Включить</PrimaryButton>
          </ConfirmsPassword>
        </div>

        <div v-else>
          <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
            <PrimaryButton v-if="confirming" type="button" class="me-3" :loading="enabling">
              Подтвердить
            </PrimaryButton>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
            <SecondaryButton v-if="recoveryCodes.length > 0 && !confirming" class="me-3">
              Обновить коды восстановления
            </SecondaryButton>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="showRecoveryCodes">
            <SecondaryButton v-if="recoveryCodes.length === 0 && !confirming" class="me-3">
              Показывать коды восстановления
            </SecondaryButton>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <SecondaryButton v-if="confirming" :disabled="disabling"> Отмена </SecondaryButton>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <DangerButton v-if="!confirming" :disabled="disabling"> Отключить </DangerButton>
          </ConfirmsPassword>
        </div>
      </div>
    </template>
  </ActionSection>
</template>
