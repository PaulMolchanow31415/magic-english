<script setup lang="ts">
import { computed } from 'vue'
import { default as PricingPlanItem } from '../entities'
import { FwbButton } from 'flowbite-vue'

const props = withDefaults(
  defineProps<{
    items: PricingPlanItem[]
    title?: string
    price?: number
    duringTheTimePeriod?: string
  }>(),
  {
    title: 'Стандартный план',
    price: 1000,
    duringTheTimePeriod: 'месяц',
  },
)

defineEmits(['selectPlan'])

const allowedItems = computed(() => props.items.filter((i) => i.isAllowed))
const disallowedItems = computed(() => props.items.filter((i) => !i.isAllowed))
</script>

<template>
  <div
    class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700"
  >
    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400">{{ title }}</h5>
    <div class="flex items-baseline text-gray-900 dark:text-white">
      <span class="text-5xl font-extrabold tracking-tight">{{ price }}</span>
      <span class="text-3xl font-semibold">₽</span>
      <span class="ms-1 text-xl font-normal text-gray-500 dark:text-gray-400">
        /{{ duringTheTimePeriod }}
      </span>
    </div>
    <ul role="list" class="space-y-5 my-7">
      <li v-for="item in allowedItems" class="flex items-center">
        <Icon
          :icon="['fas', 'circle-check']"
          class="pricing-card-icon text-blue-700 dark:text-blue-500"
        />
        <span class="pricing-card-description dark:text-gray-400">{{ item.description }}</span>
      </li>

      <li v-for="item in disallowedItems" class="flex line-through decoration-gray-500">
        <Icon
          :icon="['fas', 'circle-check']"
          class="pricing-card-icon text-gray-400 dark:text-gray-500"
        />
        <span class="pricing-card-description">{{ item.description }}</span>
      </li>
    </ul>
    <FwbButton @click="$emit('selectPlan')" type="button" class="w-full text-center">
      Выбрать
    </FwbButton>
  </div>
</template>

<style scoped lang="postcss">
.pricing-card-icon {
  @apply flex-shrink-0 w-4 h-4;
}
.pricing-card-description {
  @apply text-base font-normal leading-tight text-gray-500 ms-3 dark:text-gray-300;
}
</style>
