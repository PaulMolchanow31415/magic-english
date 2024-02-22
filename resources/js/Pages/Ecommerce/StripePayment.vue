<script setup>
const props = defineProps({ payload: Object })

// open stripe form page
open(props.payload.url)

// close current window
close()
</script>

<template></template>

<style scoped></style>