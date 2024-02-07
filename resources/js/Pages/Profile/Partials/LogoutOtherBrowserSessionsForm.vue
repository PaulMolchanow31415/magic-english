<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ActionSection from '@/Jetstream/ActionSection.vue'
import DialogModal from '@/Shared/DialogModal.vue'
import PrimaryButton from '@/Shared/PrimaryButton.vue'
import SecondaryButton from '@/Shared/SecondaryButton.vue'
import InputError from '@/Shared/InputError.vue'
import FocusableInput from '@/Shared/FocusableInput.vue'

defineProps({ sessions: Array })

const emit = defineEmits(['success'])

const confirmingLogout = ref(false)
const passwordInput = ref(null)

const form = useForm({ password: '' })

const confirmLogout = () => {
  confirmingLogout.value = true

  setTimeout(() => passwordInput.value.focus(), 250)
}

const logoutOtherBrowserSessions = () => {
  form.delete(route('other-browser-sessions.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      emit('success')
      closeModal()
    },
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  })
}

const closeModal = () => {
  confirmingLogout.value = false

  form.reset()
}
</script>

<template>
  <ActionSection>
    <template #title> Сеансы браузера </template>

    <template #description>
      Управляйте своими активными сеансами и выходите из них в других браузерах и устройствах.
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        При необходимости вы можете выйти из всех других сеансов вашего браузера на всех ваших
        устройствах. устройства. Ниже перечислены некоторые из ваших последних сеансов; однако этот
        список может быть неполным. Если вы считаете, что ваша учетная запись была взломана, вам
        также следует обновить свой пароль.
      </div>

      <!-- Other Browser Sessions -->
      <div v-if="sessions.length > 0" class="mt-5 space-y-6">
        <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
          <div>
            <Icon
              v-if="session.agent.is_desktop"
              :icon="['fas', 'desktop']"
              size="lg"
              class="text-gray-700 dark:text-gray-300"
            />

            <Icon
              v-else
              :icon="['fas', 'mobile-screen-button']"
              size="lg"
              class="text-gray-700 dark:text-gray-300"
            />
          </div>

          <div class="ms-3">
            <div class="text-sm text-gray-600 dark:text-gray-400">
              {{ session.agent.platform ? session.agent.platform : 'Unknown' }} -
              {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
            </div>

            <div>
              <div class="text-xs text-gray-500">
                {{ session.ip_address }},

                <span v-if="session.is_current_device" class="text-green-500 font-semibold">
                  Это устройство
                </span>
                <span v-else>Последнее использование {{ session.last_active }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center mt-5">
        <PrimaryButton @click="confirmLogout">
          Закрыть все неиспользуемые сеансы браузеров
        </PrimaryButton>
      </div>

      <!-- Log Out Other Devices Confirmation Modal -->
      <DialogModal :show="confirmingLogout" @close="closeModal">
        <template #title>Закрыть все неиспользуемые сеансы браузеров</template>

        <template #content>
          Пожалуйста, введите свой пароль, чтобы подтвердить, что вы хотите выйти из другого
          браузера сеансы на всех ваших устройствах.

          <div class="mt-4">
            <FocusableInput
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="mt-1 block w-3/4"
              placeholder="Пароль"
              autocomplete="current-password"
              @keyup.enter="logoutOtherBrowserSessions"
            />

            <InputError :message="form.errors.password" class="mt-2" />
          </div>
        </template>

        <template #footer>
          <SecondaryButton @click="closeModal">Отмена</SecondaryButton>

          <PrimaryButton
            class="ms-3"
            :processing="form.processing"
            @click="logoutOtherBrowserSessions"
          >
            Закрыть все неиспользуемые сеансы
          </PrimaryButton>
        </template>
      </DialogModal>
    </template>
  </ActionSection>
</template>
