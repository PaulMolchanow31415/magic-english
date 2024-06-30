<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import { FwbButton, FwbP } from 'flowbite-vue'
import CloseButton from '@/Shared/CloseButton.vue'
import OpacitySlideTopTransition from '@/Animations/OpacitySlideTopTransition.vue'

const page = usePage()
const isShow = ref(false)

if (!page.props.isAcceptCookies) {
  setTimeout(() => (isShow.value = true), 10_000)
}

function accept() {
  router.post(route('accept-cookies'), null, {
    preserveScroll: true,
    preserveState: true,
  })
  isShow.value = false
}
</script>

<template>
  <Teleport to="body">
    <OpacitySlideTopTransition>
      <form
        v-show="isShow"
        @submit.prevent="accept"
        method="post"
        class="fixed bottom-5 left-5 z-50 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-white rounded-xl py-3 px-4 max-w-sm"
      >
        <div class="flex items-center justify-between gap-4">
          <div class="flex gap-4 items-center mb-2">
            <Icon :icon="['fas', 'cookie-bite']" size="2xl" />
            <FwbP class="font-bold">Наш сайт использует куки</FwbP>
          </div>
          <CloseButton @close="isShow = false" />
        </div>
        <hr class="mt-1.5 mb-3" />
        <FwbP class="text-xs">
          Мы используем файлы cookie для улучшения работы сайта. Оставаясь на нашем сайте вы
          соглашаетесь с условиями использованием файлов cookies
        </FwbP>
        <FwbButton color="green" type="submit" class="w-full mt-4">Согласен</FwbButton>
      </form>
    </OpacitySlideTopTransition>
  </Teleport>
</template>

<style scoped></style>
