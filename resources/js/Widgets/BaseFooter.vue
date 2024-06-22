<script setup>
import { FwbFooter, FwbFooterCopyright } from 'flowbite-vue'
import ApplicationMark from '@/Shared/ApplicationMark.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <FwbFooter footer-type="logo">
    <div class="w-full container mx-auto">
      <div class="sm:flex sm:items-center sm:justify-between">
        <ul
          class="flex flex-wrap items-center mt-4 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0 gap-x-8 gap-y-4"
        >
          <li><Link class="hover:underline" href="/public">Главная</Link></li>
          <li><Link class="hover:underline" :href="route('singer.list')">Музыка</Link></li>
          <li><Link class="hover:underline" :href="route('ecommerce')">Магазин</Link></li>
          <li>
            <Link class="hover:underline" :href="route('skills.self-education')">
              Самоучитель
            </Link>
          </li>
        </ul>

        <Link class="shrink-0 hidden sm:block" href="/public">
          <ApplicationMark class="block h-9 w-auto" />
        </Link>
      </div>
      <hr class="my-4 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-6" />
      <FwbFooterCopyright by="Magic English™" copyright-message="Все права сохранены" />
    </div>
  </FwbFooter>
</template>

<style scoped></style>
