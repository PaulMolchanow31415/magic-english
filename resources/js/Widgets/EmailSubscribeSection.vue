<script setup>
import { FwbA, FwbButton, FwbInput } from 'flowbite-vue'
import { useForm } from '@inertiajs/vue3'
import { useReCaptcha } from 'vue-recaptcha-v3'
import { useDeviceSize, useFlashMessages } from '@/Composables'

const form = useForm({ email: '', recaptcha_token: null })
const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()
const { isMobile } = useDeviceSize()
const { showMessage } = useFlashMessages({ closable: true, timeout: 3000 })

async function submit() {
  await recaptchaLoaded()

  form.recaptcha_token = await executeRecaptcha('subscribeNotifications')

  form.post(route('subscribe.store'), {
    onSuccess: () => showMessage('Вы успешно подписались!', 'success'),
    onError: () => showMessage(form.errors.email || 'Ошибка', 'warning'),
    onFinish: () => {
      form.email = ''
      form.recaptcha_token = null
    },
    preserveScroll: true,
  })
}
</script>

<template>
  <aside
    class="p-4 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 lg:p-8 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Подписка на рассылку курсов"
  >
    <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Получайте уведомления...</h3>

    <p class="mb-5 text-sm font-medium text-gray-500 dark:text-gray-300">
      Подпишитесь на рассылку новых курсов и нопоминаний об учебе
    </p>
    <form @submit.prevent="submit" method="post">
      <div class="flex items-end mb-2">
        <div class="flex items-center gap-3 w-full md:max-w-lg">
          <FwbInput
            @focus="form.email = $page.props.auth.user?.email || ''"
            v-model="form.email"
            placeholder="Ваш email адрес"
            type="email"
            size="lg"
            required
            class="w-full"
            :validation-status="form.errors.email ? 'error' : ''"
          >
            <template #prefix>
              <Icon
                :icon="['fas', 'envelope']"
                size="lg"
                class="text-gray-500 dark:text-gray-400"
              />
            </template>
            <template #suffix v-if="!isMobile">
              <FwbButton :loading="form.processing" class="fwb-btn" type="submit">
                Подписаться
              </FwbButton>
            </template>
            <template #validationMessage>{{ form.errors.email }}</template>
          </FwbInput>
        </div>
      </div>
      <!--  Mobile button  -->
      <FwbButton
        v-if="isMobile"
        :loading="form.processing"
        size="lg"
        class="fwb-btn justify-center w-full mb-3"
        type="submit"
      >
        Подписаться
      </FwbButton>
    </form>
    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
      Подписываясь, вы соглашаетесь с нашей
      <FwbA target="_blank" :href="route('policy.show')" class="text-blue-600 dark:text-blue-500">
        политикой конфиденциальности
      </FwbA>
      <span>&nbsp;и&nbsp;</span>
      <FwbA target="_blank" :href="route('terms.show')" class="text-blue-600 dark:text-blue-500">
        условиями обслуживания.
      </FwbA>
    </div>
  </aside>
</template>

<style scoped lang="postcss"></style>
