<template>
  <Transition
    enter-active-class="transition ease duration-200"
    leave-active-class="transition ease duration-200"
    enter-from-class="opacity-0"
    leave-to-class="opacity-0"
  >
    <slot />
  </Transition>
</template>
