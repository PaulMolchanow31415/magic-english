<script setup>
const props = defineProps({ payload: Object })

open(props.payload.url)
close()
</script>

<template></template>

<style scoped></style>