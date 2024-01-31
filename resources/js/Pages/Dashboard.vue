<script setup>
import ApplicationLogo from '@/Shared/ApplicationLogo.vue'
import DiscussionSection from '@/Shared/DiscussionSection.vue'

const translatedText = `
<h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
  Welcome to your Jetstream application!
</h1>

<p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
  <b>Jetstream</b>     provides a beautiful, robust starting point for your next Laravel
  application. Laravel is designed to help you build your application using a development
  environment that is simple,  <i > Русское слово  </i>, and enjoyable. We believe you should love
  expressing your creativity through programming, so we have spent time carefully crafting
  the Laravel ecosystem to be a breath of fresh air. We hope you love it.
</p><p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
  <b>Jetstream</b>     provides a beautiful, robust starting point for your next Laravel
  application. Laravel is designed to help you build your application using a development
  environment that is simple,  <i > Русское слово  </i>, and enjoyable. We believe you should love
  expressing your creativity through programming, so we have spent time carefully crafting
  the Laravel ecosystem to be a breath of fresh air. We hope you love it.
</p>
`
</script>

<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
        <div
          class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700"
        >
          <ApplicationLogo class="block h-12 w-auto" />

          <div v-translatable v-html="translatedText" />
        </div>

        <DiscussionSection :discussion-id="2" />
      </div>
    </div>
  </div>
</template>
