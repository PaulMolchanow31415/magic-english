<script setup>
import { Head, router } from '@inertiajs/vue3'
import { FwbButton } from 'flowbite-vue'
import EmailSubscribeSection from '@/Widgets/EmailSubscribeSection.vue'
import { ref } from 'vue'
import { Accordion, AccordionItem } from '@/Shared/Accordion'

defineProps({ faqs: Array })

const subscribeSection = ref(null)
</script>

<template>
  <Head title="Главная" />

  <main class="mx-auto max-w-screen-md">
    <section class="py-10 lg:py-32 sm:text-center">
      <h1 class="heading-1 mb-4 sm:mb-8">Эстетичный подход к изучению английского языка</h1>
      <p class="mb-8 description">
        Онлайн-самоучитель позволит поднять английский на нормальный уровень. Уже через месяц вы
        сможете с легкостью выражать свои мысли, понимать собеседников, понимать фильмы в оригинале,
        зарубежные форумы, переводить текст песен. <br />
        🎞 📻 📖 📋 💎
      </p>
      <div class="flex flex-col space-y-2 sm:flex-row sm:justify-center sm:space-y-0 gap-3">
        <FwbButton
          @click="router.visit(route('skills.self-education'))"
          size="xl"
          class="justify-center"
        >
          Начать
          <template #suffix>
            <Icon :icon="['fas', 'arrow-right']" size="sm" class="-mb-0.5" />
          </template>
        </FwbButton>
        <FwbButton
          @click="subscribeSection.scrollIntoView({ behavior: 'smooth' })"
          color="alternative"
          size="xl"
        >
          Оформить подписку
        </FwbButton>
      </div>
    </section>

    <section v-if="faqs.length > 0" class="py-14 md:py-24 lg:py-32">
      <h2 class="heading-2 mb-6">Ответы на вопросы</h2>
      <Accordion always-open open-first>
        <AccordionItem v-for="faq in faqs" :key="faq.id">
          <template #heading>{{ faq.heading }}</template>
          <p v-html="faq.content" class="accordion-content" />
        </AccordionItem>
      </Accordion>
    </section>

    <section ref="subscribeSection" class="pt-12 mb-28">
      <h2 class="heading-2 mb-6">Оформление подписки</h2>
      <EmailSubscribeSection />
    </section>
  </main>
</template>
