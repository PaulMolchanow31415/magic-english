<script setup>
import { nextTick, reactive, ref } from 'vue'
import DialogModal from '@/Pages/Auth/Partials/DialogModal.vue'
import PrimaryButton from '@/Shared/PrimaryButton.vue'
import SecondaryButton from '@/Shared/SecondaryButton.vue'
import FocusableInput from '@/Shared/FocusableInput.vue'
import InputError from '@/Shared/InputError.vue'
import {
  doConfirmPassword,
  getPasswordConfirmStatus,
} from '@/Pages/Auth/Partials/ConfirmsPassword/api'

const emit = defineEmits(['confirmed'])

defineProps({
  title: {
    type: String,
    default: 'Подтвердите пароль',
  },
  content: {
    type: String,
    default: 'В целях вашей безопасности, пожалуйста, подтвердите свой пароль, чтобы продолжить.',
  },
  buttonText: {
    type: String,
    default: 'Подтвердить',
  },
})

const confirmingPassword = ref(false)

const form = reactive({
  password: '',
  error: '',
  processing: false,
})

const passwordInput = ref(null)

const startConfirmingPassword = () => {
  getPasswordConfirmStatus().then((response) => {
    if (response.data.confirmed) {
      emit('confirmed')
    } else {
      confirmingPassword.value = true

      setTimeout(() => passwordInput.value.focus(), 250)
    }
  })
}

const confirmPassword = () => {
  form.processing = true

  doConfirmPassword(form.password)
    .then(() => {
      form.processing = false

      closeModal()
      nextTick().then(() => emit('confirmed'))
    })
    .catch((error) => {
      form.processing = false
      form.error = error.response.data.errors.password[0]
      passwordInput.value.focus()
    })
}

const closeModal = () => {
  confirmingPassword.value = false
  form.password = ''
  form.error = ''
}
</script>

<template>
  <span>
    <span @click="startConfirmingPassword">
      <slot />
    </span>

    <DialogModal :show="confirmingPassword" @close="closeModal">
      <template #title>
        {{ title }}
      </template>

      <template #content>
        {{ content }}

        <div class="mt-4">
          <FocusableInput
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-3/4"
            placeholder="Пароль"
            autocomplete="current-password"
            @keyup.enter="confirmPassword"
          />
          <InputError :message="form.error" class="mt-2" />
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>

        <PrimaryButton class="ms-3" :processing="form.processing" @click="confirmPassword">
          {{ buttonText }}
        </PrimaryButton>
      </template>
    </DialogModal>
  </span>
</template>
