<script setup>
import {
  FwbAvatar,
  FwbButton,
  FwbHeading,
  FwbListGroup,
  FwbListGroupItem,
  FwbP,
} from 'flowbite-vue'
import Badge from '@/Shared/Badge.vue'
import { Head, router } from '@inertiajs/vue3'
import { useDeviceSize, useFlashMessages } from '@/Composables'

defineProps({ products: Array })

const { showMessage } = useFlashMessages()
const isTablet = useDeviceSize().isGreaterThen(639)

function handleRemove(product) {
  router.delete(route('cart.remove-product', product.pivot.product_id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => showMessage('Успешно удалено!', 'success'),
    onError: () => showMessage('Ошибка, попробуйте перезагрузить страницу', 'warning'),
  })
}
</script>

<template>
  <Head :title="`Корзина (${products.length})`" />

  <div class="container pt-12 pb-16 mx-auto">
    <section class="max-w-5xl mx-auto">
      <template v-if="products.length > 0">
        <FwbHeading tag="h2" class="text-center mb-8">Корзина ({{ products.length }})</FwbHeading>
        <FwbListGroup class="w-full">
          <FwbListGroupItem
            v-for="product in products"
            :key="product.pivot.product_id"
            :title="product.name"
            class="gap-4 sm:gap-2 flex-col sm:flex-row py-4 sm:py-2"
          >
            <template #prefix>
              <FwbAvatar
                :size="isTablet ? 'md' : 'xl'"
                :img="product.poster_url"
                :initials="$helpers.avatarInitials(product.name)"
              />
            </template>

            <div class="grow flex justify-between gap-4 font-semibold text-lg">
              <FwbP v-text="product.name" class="line-clamp-1 mb-0" />
              <Badge>{{ product.price }}₽</Badge>
            </div>

            <template #suffix>
              <FwbButton
                @click="handleRemove(product)"
                pill
                :square="isTablet"
                :size="isTablet ? 'sm' : 'lg'"
                color="alternative"
                class="transition duration-75 hover:text-red-600 active:text-red-700 sm:w-10 sm:h-10"
              >
                <span class="sm:sr-only me-2">Удалить</span>
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

        <div class="mt-12 flex flex-wrap justify-center gap-4">
          <FwbButton
            type="button"
            color="alternative"
            @click="router.delete(route('cart.clear'))"
            class="px-12 w-full sm:w-auto"
          >
            Очистить все
          </FwbButton>

          <FwbButton
            class="px-12 w-full sm:w-auto"
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
