<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import HorizontalLine from '@/Shared/HorizontalLine.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuickEnableRef } from '@/Composables/index.js'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'
import { set } from '@vueuse/core'

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
})

const profileInfoSaved = ref(false)
const passwordSaved = ref(false)
const sessionClosed = ref(false)

const showProfileInfo = () => useQuickEnableRef(profileInfoSaved)
const showPasswordInfo = () => useQuickEnableRef(passwordSaved)
const showSessionCloseInfo = () => useQuickEnableRef(sessionClosed)

function closeToast(index) {
  if (index === 0) {
    set(profileInfoSaved, false)
  } else if (index === 1) {
    set(passwordSaved, false)
  } else if (index === 2) {
    set(sessionClosed, false)
  }
}
</script>

<template>
  <Head title="Профиль" />

  <Toaster
    closable
    @close="closeToast"
    :tosts="[
      new Toast({ type: 'success', isShow: profileInfoSaved, value: 'Успешно сохранено' }),
      new Toast({ type: 'success', isShow: passwordSaved, value: 'Пароль успешно обновлен' }),
      new Toast({ type: 'info', isShow: sessionClosed, value: 'Сессии закрыты' }),
    ]"
  />

  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
      <UpdateProfileInformationForm @success="showProfileInfo" :user="$page.props.auth.user" />

      <HorizontalLine />
    </div>

    <div v-if="$page.props.jetstream.canUpdatePassword">
      <UpdatePasswordForm @success="showPasswordInfo" class="mt-10 sm:mt-0" />

      <HorizontalLine />
    </div>

    <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
      <TwoFactorAuthenticationForm
        :requires-confirmation="confirmsTwoFactorAuthentication"
        class="mt-10 sm:mt-0"
      />

      <HorizontalLine />
    </div>

    <LogoutOtherBrowserSessionsForm
      @success="showSessionCloseInfo"
      :sessions="sessions"
      class="mt-10 sm:mt-0"
    />

    <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
      <HorizontalLine />

      <DeleteUserForm class="mt-10 sm:mt-0" />
    </template>
  </div>
</template>
