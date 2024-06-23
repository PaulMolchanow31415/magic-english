<script setup>
import { FwbInput } from 'flowbite-vue'
import InputLabel from '@/Shared/InputLabel.vue'

defineProps({
  min: {
    type: Number,
    default: 10,
  },
  max: {
    type: Number,
    default: 10000,
  },
  errorMessage: String,
})

const model = defineModel({ type: Number })
</script>

<template>
  <InputLabel value="Цена">
    <div class="flex items-start mb-4">
      <div
        class="py-2.5 px-4 flex-shrink-0 inline-flex items-center text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
      >
        <svg
          class="h-4 w-4 me-2"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 -4 28 28"
          fill="none"
        >
          <g clip-path="url(#clip0_503_2726)">
            <rect
              x="0.25"
              y="0.25"
              width="27.5"
              height="19.5"
              rx="1.75"
              fill="white"
              stroke="#F5F5F5"
              stroke-width="0.5"
            />
            <mask
              id="mask0_503_2726"
              style="mask-type: alpha"
              maskUnits="userSpaceOnUse"
              x="0"
              y="0"
              width="28"
              height="20"
            >
              <rect
                x="0.25"
                y="0.25"
                width="27.5"
                height="19.5"
                rx="1.75"
                fill="white"
                stroke="white"
                stroke-width="0.5"
              />
            </mask>
            <g mask="url(#mask0_503_2726)">
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 13.3333H28V6.66667H0V13.3333Z"
                fill="#0C47B7"
              />
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 20H28V13.3333H0V20Z"
                fill="#E53B35"
              />
            </g>
          </g>
          <defs>
            <clipPath id="clip0_503_2726">
              <rect width="28" height="20" rx="2" fill="white" />
            </clipPath>
          </defs>
        </svg>
        РУБ
      </div>
      <div class="relative w-full">
        <FwbInput
          v-model="model"
          type="number"
          id="currency-input"
          class="rounded-s-none border-s-0"
          :min="min"
          :max="max"
          placeholder="Цена"
          :validation-status="errorMessage ? 'error' : ''"
        >
          <template #validationMessage>{{ errorMessage }}</template>
        </FwbInput>
      </div>
    </div>
  </InputLabel>
</template>
