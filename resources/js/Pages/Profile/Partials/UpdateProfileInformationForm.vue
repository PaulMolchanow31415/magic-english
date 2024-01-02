<script setup>
import { ref } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { FwbInput } from 'flowbite-vue'

const props = defineProps({
  user: Object,
})

const emit = defineEmits(['success'])

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  photo: null,
})

const verificationLinkSent = ref(false)
const photoPreview = ref(null)
const photoInput = ref(null)

const updateProfileInformation = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  form.put(route('user-profile-information.update'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => {
      emit('success')
      clearPhotoFileInput()
    },
  })
}

const sendEmailVerification = () => (verificationLinkSent.value = true)

const selectNewPhoto = () => photoInput.value.click()

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0]

  if (!photo) {
    return
  }

  const reader = new FileReader()

  reader.onload = (e) => (photoPreview.value = e.target.result)

  reader.readAsDataURL(photo)
}

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null
      clearPhotoFileInput()
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
}
</script>

<template>
  <FormSection @submitted="updateProfileInformation">
    <template #title>Данные профиля</template>

    <template #description>
      Обновляет информацию профиля вашей учетной записи и адрес электронной почты.
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
        <!-- Profile Photo File Input -->
        <input
          id="photo"
          ref="photoInput"
          type="file"
          class="hidden"
          @change="updatePhotoPreview"
        />

        <InputLabel for="photo" value="Фотография">
          <!-- Current Profile Photo -->
          <div v-show="!photoPreview">
            <img
              :src="user.profile_photo_url"
              :alt="user.name"
              class="rounded-full h-20 w-20 object-cover"
            />
          </div>

          <!-- New Profile Photo Preview -->
          <div v-show="photoPreview" class="mt-2">
            <span
              class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
              :style="'background-image: url(\'' + photoPreview + '\');'"
            />
          </div>
        </InputLabel>

        <SecondaryButton class="mt-4 me-2" type="button" @click.prevent="selectNewPhoto">
          Выберите новую фотографию
        </SecondaryButton>

        <SecondaryButton
          v-if="user.profile_photo_path"
          type="button"
          class="mt-4"
          @click.prevent="deletePhoto"
        >
          Удалить фотографию
        </SecondaryButton>

        <InputError :message="form.errors.photo" class="mt-2" />
      </div>

      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel value="Имя">
          <FwbInput
            v-model="form.name"
            type="text"
            required
            autocomplete="name"
            :validation-status="form.errors.name ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.name }}</template>
          </FwbInput>
        </InputLabel>
      </div>

      <!-- Email -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel value="Email">
          <FwbInput
            v-model="form.email"
            type="email"
            required
            autocomplete="username"
            :validation-status="form.errors.email ? 'error' : ''"
          >
            <template #validationMessage>{{ form.errors.email }}</template>
          </FwbInput>
        </InputLabel>

        <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
          <p class="text-sm mt-2 dark:text-white">
            Ваш адрес электронной почты не подтвержден.

            <Link
              :href="route('verification.send')"
              method="post"
              as="button"
              class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
              @click.prevent="sendEmailVerification"
            >
              Нажмите здесь, чтобы повторно отправить электронное письмо с подтверждением.
            </Link>
          </p>

          <div
            v-show="verificationLinkSent"
            class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
          >
            На ваш адрес электронной почты была отправлена новая ссылка для подтверждения.
          </div>
        </div>
      </div>
    </template>

    <template #actions>
      <PrimaryButton :processing="form.processing">Сохранить</PrimaryButton>
    </template>
  </FormSection>
</template>
