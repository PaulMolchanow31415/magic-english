<script setup>
import {
  FwbAvatar,
  FwbButton,
  FwbHeading,
  FwbListGroup,
  FwbListGroupItem,
  FwbP,
} from 'flowbite-vue'
import { inject, ref } from 'vue'
import Badge from '@/Shared/Badge.vue'
import { Head, router } from '@inertiajs/vue3'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'

defineProps({ products: Array })

const avatarInitials = inject('avatarInitials')

const isRemoved = ref(false)
const isError = ref(false)

function handleRemove(product) {
  router.delete(route('cart.remove-product', product.pivot.product_id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => useQuickEnableRef(isRemoved),
    onError: () => useQuickEnableRef(isError),
  })
}
</script>

<template>
  <Head :title="`Корзина (${products.length})`" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', value: 'Успешно удалено!', isShow: isRemoved }),
      new Toast({
        type: 'warning',
        value: 'Ошибка, попробуйте перезагрузить страницу',
        isShow: isError,
      }),
    ]"
  />

  <div class="container pt-12 pb-16 mx-auto">
    <section class="max-w-5xl mx-auto">
      <template v-if="products.length > 0">
        <FwbHeading tag="h2" class="text-center mb-8">Корзина ({{ products.length }})</FwbHeading>
        <FwbListGroup class="w-full">
          <FwbListGroupItem
            v-for="product in products"
            :key="product.pivot.product_id"
            :title="product.name"
            class="gap-2 group"
          >
            <template #prefix>
              <FwbAvatar
                size="md"
                :img="product.poster_url"
                :initials="avatarInitials(product.name)"
              />
            </template>

            <div class="grow flex justify-between gap-4 font-semibold text-lg">
              <FwbP v-text="product.name" class="line-clamp-1" />
              <Badge>{{ product.price }}₽</Badge>
            </div>

            <template #suffix>
              <FwbButton
                @click="handleRemove(product)"
                pill
                square
                color="alternative"
                class="group-hover:opacity-100 opacity-0 transition duration-75 hover:text-red-600 active:text-red-700 w-10 h-10"
              >
                <span class="sr-only">Удалить</span>
                <Icon :icon="['fas', 'trash-can']" />
              </FwbButton>
            </template>
          </FwbListGroupItem>
        </FwbListGroup>

        <FwbP class="text-center pt-4 font-bold text-xl">
          Итого:
          <span
            class="font-extrabold"
            v-text="products.reduce((sum, current) => current.price + sum, 0) + ' ₽'"
          />
        </FwbP>

        <div class="mt-12 flex justify-center gap-4">
          <FwbButton
            type="button"
            color="alternative"
            @click="router.delete(route('cart.clear'))"
            class="px-12"
          >
            Очистить все
          </FwbButton>

          <FwbButton
            class="px-12"
            size="xl"
            type="button"
            gradient="green"
            @click="router.visit(route('cart.checkout'))"
          >
            Перейти к оплате
          </FwbButton>
        </div>
      </template>

      <template v-else>
        <FwbHeading class="text-center mt-[12vh]" tag="h6">Корзина пуста</FwbHeading>

        <div class="mt-6 flex justify-center">
          <FwbButton
            class="px-24"
            size="xl"
            type="button"
            @click="router.visit(route('ecommerce'))"
          >
            Перейти в каталог
          </FwbButton>
        </div>
      </template>
    </section>
  </div>
</template>

<style scoped></style>