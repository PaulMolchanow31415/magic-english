<script setup>
function enter(element) {
  element.style.width = getComputedStyle(element).width
  element.style.position = 'absolute'
  element.style.visibility = 'hidden'
  element.style.height = 'auto'

  const height = getComputedStyle(element).height

  element.style.width = null
  element.style.position = null
  element.style.visibility = null
  element.style.height = 0

  // Принудительно перерисовка, чтобы убедиться, что анимация запущена правильно.
  getComputedStyle(element).height

  // Запуск анимации.
  // Мы используем `requestAnimationFrame`, потому что нам нужно
  // убедиться, что браузер завершил рисование после установки `height`
  // равным `0` в строке выше.
  requestAnimationFrame(() => (element.style.height = height))
}

function afterEnter(element) {
  element.style.height = 'auto'
}

function leave(element) {
  element.style.height = getComputedStyle(element).height

  // Принудительно перерисовка, чтобы убедиться, что анимация запущена правильно.
  getComputedStyle(element).height

  requestAnimationFrame(() => (element.style.height = 0))
}
</script>

<template>
  <Transition name="expand" @enter="enter" @after-enter="afterEnter" @leave="leave">
    <slot />
  </Transition>
</template>

<style scoped>
* {
  will-change: height;
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000px;
}

.expand-enter-active,
.expand-leave-active {
  transition: height 0.4s ease-in-out;
  overflow: hidden;
}

.expand-enter,
.expand-leave-to {
  height: 0;
}
</style>
