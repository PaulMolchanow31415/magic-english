<script setup>
import { Head } from '@inertiajs/vue3'
import { useGlobalState } from '@/Composables/useGlobalState.js'
import { FwbListGroup, FwbListGroupItem } from 'flowbite-vue'

const props = defineProps({ musics: Array, singer: Object })

const state = useGlobalState()

function onClick(music) {
  state.value.song = music
  state.value.singer = props.singer
}
</script>

<template>
  <Head title="Музыка" />

  <section class="py-10 lg:py-32 px-1 max-w-4xl mx-auto">
    <h2 class="heading-2 mb-4 sm:mb-8 text-center">Все песни {{ singer.name }}</h2>

    <FwbListGroup class="w-full">
      <FwbListGroupItem v-for="(music, index) in musics" :key="music.id" class="gap-2 group">
        <template #prefix>
          <button
            @click="onClick(music)"
            type="button"
            class="flex justify-center items-center w-10 h-10"
          >
            <span class="group-hover:hidden">{{ index + 1 }}</span>
            <Icon :icon="['fas', 'play']" class="hidden group-hover:block" />
            <span class="sr-only">Включить</span>
          </button>
        </template>

        <button
          @click="onClick(music)"
          v-text="music.name"
          type="button"
          class="text-left grow text-lg"
        />
      </FwbListGroupItem>
    </FwbListGroup>

    <blockquote v-html="singer.biography" class="blockquote-bordered sm:mt-12" />
  </section>
</template>

<style scoped></style>
