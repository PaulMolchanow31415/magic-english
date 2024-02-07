<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'

const wrapper = ref(null)

function handleClick(e) {
  // e = e || event
  // get selection
}

function handleClickOutside() {
  //
}

onClickOutside(wrapper, handleClickOutside)
</script>

<template>
  <div @click="handleClick" ref="wrapper">
    <slot />
  </div>
</template>

<style scoped></style>