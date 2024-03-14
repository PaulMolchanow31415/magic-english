<script setup>
import { useRecaptchaProvider } from 'vue-recaptcha'

useRecaptchaProvider()
</script>

<template>
  <div><slot /></div>
</template>
