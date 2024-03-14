<script setup>
import { Head, usePage } from '@inertiajs/vue3'

const { title } = usePage().props.breadcrumbs.find((b) => b.current)
</script>

<template>
  <Head :title="title" />
</template>
