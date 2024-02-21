<script>
import { defineComponent } from 'vue'
import { FwbButton } from 'flowbite-vue'

export default defineComponent({
  name: 'EcommerceCard',
  components: { FwbButton },
  props: {
    imgSrc: String,
    imgAlt: String,
    title: String,
    price: Number,
  },
  emits: ['addToCart'],
})
</script>

<template>
  <div
    class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
    :title="title"
  >
    <img class="pb-8 w-full rounded-t-lg" :src="imgSrc" :alt="imgAlt" />

    <div class="px-5 pb-5">
      <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white line-clamp-1">
        {{ title }}
      </h5>

      <div class="flex items-center justify-between pt-5">
        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ price }}₽</span>
        <FwbButton
          @click="$emit('addToCart')"
          outline
          type="button"
          title="Добавить в корзину"
          class="ps-[0.95rem] pe-[1rem] py-2.5"
        >
          <Icon :icon="['fas', 'cart-plus']" size="lg" />
          <span class="sr-only"> Добавить в корзину </span>
        </FwbButton>
      </div>
    </div>
  </div>
</template>

<style scoped></style>