<script setup></script>

<template>
  <teleport to="body">
    <div class="fixed right-5 top-5 flex flex-col gap-2.5">
      <transition-group
        leave-active-class="transition ease-in duration-1000"
        leave-from-class="opacity-100 transform"
        leave-to-class="opacity-0 translate-x-1/2"
        enter-from-class="opacity-0 translate-x-1/2"
        enter-to-class="duration-500 opacity-100"
      >
        <slot />
      </transition-group>
    </div>
  </teleport>
</template>

<style scoped></style>
