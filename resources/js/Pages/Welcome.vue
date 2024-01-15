<script setup>
import { Head, router } from '@inertiajs/vue3'
import {
  FwbAccordion,
  FwbAccordionContent,
  FwbAccordionHeader,
  FwbAccordionPanel,
  FwbButton,
  FwbHeading,
} from 'flowbite-vue'
import EmailSubscribe from '@/Shared/EmailSubscribe.vue'

defineProps({ faqs: Array })
</script>

<template>
  <Head title="Главная" />

  <div class="mx-auto max-w-screen-md">
    <section class="py-10 px-4 lg:py-32 text-center">
      <FwbHeading tag="h1" class="mb-8">
        Эстетичный подход к изучению английского языка
      </FwbHeading>
      <p class="px-14 mb-14 text-lg text-gray-700 lg:text-xl dark:text-gray-200">
        Онлайн-самоучитель позволит поднять английский на нормальный уровень. Уже через месяц вы
        сможете с легкостью выражать свои мысли, понимать собеседников, смотреть и понимать фильмы в
        оригинале, зарубежные форумы, переводить и понимать текст песен. <br />
        🎞 📻 📖 📋 💎
      </p>
      <div class="flex flex-col space-y-2 sm:flex-row sm:justify-center sm:space-y-0 gap-3">
        <FwbButton @click="router.visit('dashboard')" size="xl" class="justify-center">
          Начать
          <template #suffix>
            <Icon :icon="['fas', 'arrow-right']" size="sm" class="-mb-0.5" />
          </template>
        </FwbButton>
        <FwbButton color="alternative" size="xl">Проверить навыки</FwbButton>
      </div>
    </section>

    <section v-if="faqs.length > 0" class="px-4 py-14 md:py-24 lg:py-32">
      <FwbHeading tag="h2" class="mb-6">Ответы на часто задаваемые вопросы</FwbHeading>
      <FwbAccordion
        class="border-b border-gray-200 dark:border-gray-700 shadow-md rounded-t-lg"
        :open-first-item="false"
      >
        <FwbAccordionPanel v-for="faq in faqs">
          <FwbAccordionHeader>{{ faq.heading }}</FwbAccordionHeader>
          <FwbAccordionContent>
            <p v-html="faq.content" class="text-gray-500 dark:text-gray-400 accordion-content" />
          </FwbAccordionContent>
        </FwbAccordionPanel>
      </FwbAccordion>
    </section>

    <section class="px-4 pt-12 mb-28">
      <FwbHeading tag="h2" class="mb-6"> Оформление подписки на предстоящие курсы</FwbHeading>
      <EmailSubscribe />
    </section>
  </div>
</template>
