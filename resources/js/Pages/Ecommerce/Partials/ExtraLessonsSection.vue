<script setup>
import { FwbButton, FwbP } from 'flowbite-vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { quickEnableRef } from '@/Helpers'
import Toaster from '@/Shared/Toaster.vue'
import { Toast } from '@/Classes'
import EcommerceCard from '@/Pages/Ecommerce/Partials/EcommerceCard.vue'

defineProps({ lessons: Array })

const isAdded = ref(false)
const isError = ref(false)

function handleAddToCart(selected) {
  router.post(route('cart.add-product', selected.id), null, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => quickEnableRef(isAdded),
    onError: () => quickEnableRef(isError),
  })
}

function onCloseToast(index) {
  switch (index) {
    case 0:
      isAdded.value = false
      break
    case 1:
      isError.value = false
      break
  }
}
</script>

<template>
  <Toaster
    closable
    @close="onCloseToast"
    :toasts="[
      new Toast({ type: 'success', value: 'Продукт добавлен в корзину!', isShow: isAdded }),
      new Toast({
        type: 'warning',
        value: 'Ошибка, попробуйте перезагрузить страницу',
        isShow: isError,
      }),
    ]"
  />

  <section class="max-w-screen-md mx-auto">
    <div class="mb-12 sm:text-center">
      <h6 class="heading-1 mb-4 md:mb-8">Дополнительные уроки</h6>
      <FwbP class="description">
        Улучшите свои навыки английского языка с помощью нашего специального набора дополнительных
        уроков. Здесь вы найдете разносторонние материалы, которые помогут вам углубить знания и
        развить навыки, необходимые для успешного обучения английского языка.
      </FwbP>
    </div>

    <div
      v-if="lessons.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-6"
    >
      <EcommerceCard
        @add-to-cart="handleAddToCart(lesson)"
        v-for="lesson in lessons"
        :key="lesson.id"
        :img-src="lesson.poster_url"
        :img-alt="lesson.name"
        :title="lesson.name"
        :price="lesson.price"
        class="place-self-stretch"
      />
    </div>

    <FwbP v-else class="text-center">
      <span class="font-medium block text-lg">Поздравляем! Вы преобрели все товары</span>
      <FwbButton
        @click="router.visit(route('purchased.lesson.all'))"
        size="lg"
        type="button"
        class="mt-3 w-full sm:w-auto"
      >
        Перейти в "Покупки"
      </FwbButton>
    </FwbP>
  </section>
</template>
