<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import Divider from '@/Shared/Divider.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'
import UpdateSubscribeStatusForm from '@/Pages/Profile/Partials/UpdateSubscribeStatusForm.vue'

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
})
</script>

<template>
  <Head title="Профиль" />

  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
      <UpdateProfileInformationForm :user="$page.props.auth.user" />

      <Divider />
    </div>

    <div v-if="$page.props.jetstream.canUpdatePassword">
      <UpdatePasswordForm class="mt-10 sm:mt-0" />

      <Divider />
    </div>

    <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
      <TwoFactorAuthenticationForm
        :requires-confirmation="confirmsTwoFactorAuthentication"
        class="mt-10 sm:mt-0"
      />

      <Divider />
    </div>

    <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

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
