<script setup>
const props = defineProps({ payload: Object })

// open stripe form page
open(props.payload.url, '_self')
</script>

<template></template>

<style scoped></style>
