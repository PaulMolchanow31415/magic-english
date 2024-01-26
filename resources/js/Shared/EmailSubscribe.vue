<script setup>
import { FwbA, FwbButton, FwbInput } from 'flowbite-vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useChallengeV3 } from 'vue-recaptcha'
import { ref } from 'vue'
import { useQuickEnableRef } from '@/Composables/index.js'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'

const page = usePage()
const isSuccess = ref(false)
const isError = ref(false)
const form = useForm({
  email: '',
  recaptcha_token: null,
})
const { execute } = useChallengeV3('subscribeNewsletter')

async function submit() {
  form.recaptcha_token = await execute()

  form.post(route('subscribe.store'), {
    onSuccess: () => useQuickEnableRef(isSuccess, 3000),
    onError: () => useQuickEnableRef(isError, 3000),
    onFinish: () => form.reset(),
    preserveScroll: true,
  })
}
</script>

<template>
  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isSuccess, value: 'Вы успешно подписались!' }),
      new Toast({ type: 'warning', isShow: isError, value: form.errors.email || 'Ошибка' }),
    ]"
  />

  <aside
    class="p-4 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 lg:p-8 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Подписка на рассылку курсов"
  >
    <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Получайте уведомления...</h3>

    <p class="mb-5 text-sm font-medium text-gray-500 dark:text-gray-300">
      Подпишитесь на рассылку новых курсов и нопоминаний об учебе
    </p>
    <form @submit.prevent="submit" method="post">
      <div class="flex items-end mb-3">
        <div class="flex items-center gap-3 w-full md:max-w-lg mb-3">
          <FwbInput
            @focus="form.email = page.props.auth.user?.email || ''"
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
            <template #suffix>
              <FwbButton :loading="form.processing" class="flex flex-nowrap" type="submit">
                Подписаться
              </FwbButton>
            </template>
            <template #validationMessage>{{ form.errors.email }}</template>
          </FwbInput>
        </div>
      </div>
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