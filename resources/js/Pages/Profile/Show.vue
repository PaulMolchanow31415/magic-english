<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import Divider from '@/Shared/Divider.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import Toaster from '@/Shared/Toaster.vue'
import { Toast } from '@/Classes'
import UpdateSubscribeStatusForm from '@/Pages/Profile/Partials/UpdateSubscribeStatusForm.vue'
import { quickEnableRef } from '@/Helpers'

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
})

const profileInfoSaved = ref(false)
const passwordSaved = ref(false)
const sessionClosed = ref(false)

const showProfileInfo = () => quickEnableRef(profileInfoSaved)
const showPasswordInfo = () => quickEnableRef(passwordSaved)
const showSessionCloseInfo = () => quickEnableRef(sessionClosed)

function closeToast(index) {
  switch (index) {
    case 0:
      profileInfoSaved.value = false
      break
    case 1:
      passwordSaved.value = false
      break
    case 2:
      sessionClosed.value = false
      break
  }
}
</script>

<template>
  <Head title="Профиль" />

  <Toaster
    closable
    @close="closeToast"
    :toasts="[
      new Toast({ type: 'success', isShow: profileInfoSaved, value: 'Успешно сохранено' }),
      new Toast({ type: 'success', isShow: passwordSaved, value: 'Пароль успешно обновлен' }),
      new Toast({ type: 'info', isShow: sessionClosed, value: 'Сессии закрыты' }),
    ]"
  />

  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
      <UpdateProfileInformationForm @success="showProfileInfo" :user="$page.props.auth.user" />

      <Divider />
    </div>

    <div v-if="$page.props.jetstream.canUpdatePassword">
      <UpdatePasswordForm @success="showPasswordInfo" class="mt-10 sm:mt-0" />

      <Divider />
    </div>

    <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
      <TwoFactorAuthenticationForm
        :requires-confirmation="confirmsTwoFactorAuthentication"
        class="mt-10 sm:mt-0"
      />

      <Divider />
    </div>

    <LogoutOtherBrowserSessionsForm
      @success="showSessionCloseInfo"
      :sessions="sessions"
      class="mt-10 sm:mt-0"
    />

    <div>
      <Divider />

      <UpdateSubscribeStatusForm class="mt-10 sm:mt-0" />
    </div>

    <div v-if="$page.props.jetstream.hasAccountDeletionFeatures">
      <Divider />

      <DeleteUserForm class="mt-10 sm:mt-0" />
    </div>
  </div>
</template>
