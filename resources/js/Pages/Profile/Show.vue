<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import HorizontalLine from '@/Components/HorizontalLine.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'
import ToastWrapper from '@/Components/ToastWrapper.vue'
import { FwbToast } from 'flowbite-vue'
import { ref } from 'vue'
import { useShowComponent } from '@/Composables/index.js'
import ToastCloseBtn from '@/Components/ToastCloseBtn.vue'

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
})

const profileInfoSaved = ref(false)
const passwordSaved = ref(false)
const sessionClosed = ref(false)

const showProfileInfo = () => useShowComponent(profileInfoSaved)
const showPasswordInfo = () => useShowComponent(passwordSaved)
const showSessionCloseInfo = () => useShowComponent(sessionClosed)
</script>

<template>
  <Head title="Профиль" />

  <ToastWrapper>
    <FwbToast type="success" v-show="profileInfoSaved">
      <template #icon><Icon :icon="['fas', 'check']" /></template>
      <div class="info-line">
        Успешно сохранено
        <ToastCloseBtn @close="profileInfoSaved = false" />
      </div>
    </FwbToast>
    <FwbToast type="success" v-show="passwordSaved">
      <template #icon><Icon :icon="['fas', 'check']" /></template>
      <div class="info-line">
        Пароль успешно обновлен
        <ToastCloseBtn @close="passwordSaved = false" />
      </div>
    </FwbToast>
    <FwbToast v-show="sessionClosed">
      <template #icon><Icon :icon="['fas', 'circle-info']" /></template>
      <div class="info-line">
        Сессии закрыты
        <ToastCloseBtn @close="sessionClosed = false" />
      </div>
    </FwbToast>
  </ToastWrapper>

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
