<script setup>
import { FwbButton, FwbHeading, FwbP } from 'flowbite-vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'
import { set } from '@vueuse/core'
import EcommerceCard from '@/Pages/Ecommerce/Partials/EcommerceCard.vue'

defineProps({ lessons: Array })

const isAdded = ref(false)
const isError = ref(false)

function handleAddToCart(selected) {
  router.post(route('cart.add-product', selected.id), null, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => useQuickEnableRef(isAdded),
    onError: () => useQuickEnableRef(isError),
  })
}

function onCloseToast(index) {
  switch (index) {
    case 0:
      set(isAdded, false)
      break
    case 1:
      set(isError, false)
      break
  }
}
</script>

<template>
  <Toaster
    closable
    @close="onCloseToast"
    :tosts="[
      new Toast({ type: 'success', value: 'Продукт добавлен в корзину!', isShow: isAdded }),
      new Toast({
        type: 'warning',
        value: 'Ошибка, попробуйте перезагрузить страницу',
        isShow: isError,
      }),
    ]"
  />

  <section>
    <div class="mb-12 xl:w-1/2">
      <FwbHeading tag="h1" class="mb-8">Дополнительные уроки</FwbHeading>
      <FwbP class="text-lg text-gray-700 lg:text-xl dark:text-gray-200">
        Улучшите свои навыки английского языка с помощью нашего специального набора дополнительных
        уроков. Здесь вы найдете разносторонние материалы, которые помогут вам углубить знания и
        развить навыки, необходимые для успешного обучения английского языка.
      </FwbP>
    </div>

    <div
      v-if="lessons.length > 0"
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
    >
      <EcommerceCard
        @add-to-cart="handleAddToCart(lesson)"
        v-for="lesson in lessons"
        :key="lesson.id"
        :img-src="lesson.poster_url"
        :img-alt="lesson.name"
        :title="lesson.name"
        :price="lesson.price"
      />
    </div>

    <FwbP v-else>
      <span class="font-medium block text-lg">Поздравляем! Вы преобрели все товары</span>
      <FwbButton
        @click="router.visit(route('purchased.lesson.all'))"
        size="lg"
        type="button"
        class="mt-3"
      >
        Перейти в "Покупки"
      </FwbButton>
    </FwbP>
  </section>
</template>

<style scoped></style>