<script setup>
import ActionSection from '@/Jetstream/ActionSection.vue'
import PrimaryButton from '@/Shared/PrimaryButton.vue'
import { useForm } from '@inertiajs/vue3'
import SecondaryButton from '@/Shared/SecondaryButton.vue'

const form = useForm({ status: false })

function loadStatus() {
  axios.get(route('subscribe.is-subscribed')).then((res) => (form.status = !!res.data))
}

function handleSubmit(isEnabled) {
  form.status = isEnabled

  form.put(route('subscribe.change-status'), {
    onSuccess: loadStatus,
    preserveScroll: true,
    replace: true,
  })
}

loadStatus()
</script>

<template>
  <ActionSection>
    <template #title>Подписка на рассылку сообщений</template>

    <template #description>Подключение подписки на рассылку сообщений по email.</template>

    <template #content>
      <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        <template v-if="form.status"> У вас уже есть подписка </template>
        <template v-else> Вы не оформили подписку </template>
      </h3>

      <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
        Подпишитесь и к вам переодически будут приходить уведомления об учебе или новых курсах. Вы
        всегда можете ее отменить.
      </div>

      <div class="mt-5">
        <SecondaryButton v-if="form.status" @click="handleSubmit(false)">
          Отказаться от подписки
        </SecondaryButton>
        <PrimaryButton v-else type="button" @click="handleSubmit(true)">
          Подписаться
        </PrimaryButton>
      </div>
    </template>
  </ActionSection>
</template>

<style scoped></style>