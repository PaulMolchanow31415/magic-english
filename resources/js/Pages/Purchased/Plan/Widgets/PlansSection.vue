<script setup>
import PricingPlan from '@/Pages/Purchased/Plan/Components/PricingPlan.vue'
import { FwbHeading, FwbP } from 'flowbite-vue'
import { default as PricingPlanItem } from '../entities'

function _(isAllowed, description) {
  return new PricingPlanItem({ isAllowed, description })
}

const plans = [
  {
    title: 'Базовый',
    price: 2000,
    items: [
      _(true, '6 дополнительных курсов'),
      _(true, 'Онлайн уроки'),
      _(true, 'Максимальная экономия'),
      _(false, 'Перенос и отмена уроков'),
      _(false, 'Сопровождение'),
    ],
  },
  {
    title: 'Комфортный',
    price: 6000,
    items: [
      _(true, '12 дополнительных курсов'),
      _(true, 'Онлайн уроки'),
      _(true, 'Перенос и отмена уроков'),
      _(true, 'Помощь команды'),
      _(false, 'Поддержка наставника'),
    ],
  },
  {
    title: 'Усиленный',
    price: 14000,
    items: [
      _(true, '20 дополнительных курсов'),
      _(true, 'Онлайн уроки'),
      _(true, 'Перенос и отмена уроков'),
      _(true, 'Помощь команды'),
      _(true, 'Поддержка наставника'),
    ],
  },
]

function handleSelectPlan(plan) {
  //
}
</script>

<template>
  <section>
    <div class="px-4 text-center mb-12">
      <FwbHeading tag="h1" class="mb-8">Наши тарифы</FwbHeading>
      <FwbP class="px-32 text-lg text-gray-700 lg:text-xl dark:text-gray-200">
        На «Базовом» тарифе вы получите максимальную экономию, на «Комфортном» будет возможность
        переносить и отменять уроки, а на «Усиленном» вас будет сопровождать методист-наставник.
      </FwbP>
    </div>
    <div class="flex items-center justify-center gap-4">
      <PricingPlan
        @select-plan="handleSelectPlan(plan)"
        v-for="plan in plans"
        :title="plan.title"
        :price="plan.price"
        :items="plan.items"
      />
    </div>
  </section>
</template>

<style scoped></style>
