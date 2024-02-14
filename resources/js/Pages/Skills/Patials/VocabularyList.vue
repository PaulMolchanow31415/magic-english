<script setup>
import {
  FwbAvatar,
  FwbButton,
  FwbHeading,
  FwbListGroup,
  FwbListGroupItem,
  FwbP,
} from 'flowbite-vue'
import { inject } from 'vue'
import { router } from '@inertiajs/vue3'
import useSay from '@/Composables/useSay.js'

defineProps({
  vocabularies: {
    type: Array,
    required: true,
  },
  removable: Boolean,
})

const avatarInitials = inject('avatarInitials')

const { isSupported, stop, isPlaying, say } = useSay()

function handleRemove(vocabulary) {
  router.delete(route('student.remove-vocabulary', { id: vocabulary.id }), {
    preserveScroll: true,
  })
}
</script>

<template>
  <FwbListGroup v-if="vocabularies.length > 0" class="w-full">
    <FwbListGroupItem v-for="vocabulary in vocabularies" :key="vocabulary.id" class="gap-2 group">
      <template #prefix>
        <FwbAvatar
          size="md"
          :img="vocabulary.poster_url"
          :initials="avatarInitials(vocabulary.en)"
        />
      </template>

      <div class="grow">
        <FwbP v-text="vocabulary.en" class="font-semibold text-lg" />

        <div class="flex gap-1.5">
          <FwbP
            v-for="(translation, index) in vocabulary.translations"
            :key="translation"
            v-text="index > 0 ? ' — ' + translation : translation"
            class="font-extralight"
          />
        </div>
      </div>

      <template #suffix>
        <div class="flex gap-3 me-2 transition duration-75 opacity-0 group-hover:opacity-100">
          <FwbButton
            v-if="isSupported"
            @click="isPlaying ? stop() : say(vocabulary.en)"
            pill
            square
            color="alternative"
            type="button"
            class="w-10 h-10"
          >
            <span class="sr-only">Озвучить</span>
            <Icon :icon="['fas', 'volume-high']" size="sm" />
          </FwbButton>

          <FwbButton
            v-if="removable"
            @click="handleRemove(vocabulary)"
            pill
            square
            color="alternative"
            class="hover:text-red-600 active:text-red-700 w-10 h-10"
          >
            <span class="sr-only">Удалить</span>
            <Icon :icon="['fas', 'trash-can']" />
          </FwbButton>
        </div>
      </template>
    </FwbListGroupItem>
  </FwbListGroup>

  <FwbHeading v-else class="text-center mt-12" tag="h6">Тут пока ничего нет</FwbHeading>
</template>

<style scoped></style>
