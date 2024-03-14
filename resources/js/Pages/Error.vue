<script setup>
import { Link, Head } from '@inertiajs/vue3'
import StubLayout from '@/Layouts/StubLayout.vue'
import { computed } from 'vue'

defineOptions({ layout: StubLayout })

const props = defineProps({ status: Number })

const title = computed(() => {
  return {
    503: 'Услуга недоступна',
    500: 'Ошибка сервера',
    404: 'Страница не найдена',
    403: 'Доступ запрещен',
  }[props.status]
})

const description = computed(() => {
  return {
    503: 'Извините, мы проводим небольшое техническое обслуживание. Пожалуйста, перезвоните в ближайшее время.',
    500: 'Whoops, что-то пошло не так на наших серверах.',
    404: 'Извините, мы не можем найти эту страницу. На домашней странице вы найдете много интересного.',
    403: 'Извините, вам запрещен доступ к этой странице.',
  }[props.status]
})
</script>

<template>
  <Head :title="title" />

  <section class="h-svh flex items-center bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
      <div class="mx-auto max-w-screen-sm text-center">
        <h1
          class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-blue-600 dark:text-blue-500"
        >
          {{ status }}
        </h1>
        <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">
          {{ title }}
        </p>
        <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">
          {{ description }}
        </p>
        <Link
          href="/"
          class="inline-flex text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-blue-900 my-4"
        >
          Перейти на главную страницу
        </Link>
      </div>
    </div>
  </section>
</template>
