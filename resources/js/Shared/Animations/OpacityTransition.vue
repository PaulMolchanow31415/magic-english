<template>
  <Transition
    enter-active-class="transition ease duration-500"
    enter-from-class="opacity-0"
    leave-to-class="opacity-0"
  >
    <slot />
  </Transition>
</template>
