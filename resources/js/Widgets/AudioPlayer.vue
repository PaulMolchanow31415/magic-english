<script setup lang="ts">
import { useMusicState } from '../Composables'
import Tooltip from '../Shared/Tooltip.vue'
import { Link } from '@inertiajs/vue3'
import { computed, onMounted, ref, watch } from 'vue'
import { FwbP, FwbRange } from 'flowbite-vue'
import OpacitySmallTransition from '../Animations/OpacitySmallTransition.vue'
import OpacitySlideTopTransition from '../Animations/OpacitySlideTopTransition.vue'
import FadeHeightTransition from '../Animations/FadeHeightTransition.vue'

const state = useMusicState()

const volume = computed(() => state.value.volume)

const player = ref<HTMLAudioElement>()
const isPlaying = ref(false)
const duration = ref(0)
const currentTime = ref(0)
const isShowVolumePopover = ref(false)
const isShowCapture = ref(false)
const isLoadingError = ref(false)

let timer: NodeJS.Timer
let hours: number
let minutes: number
let sec: number

function formatTime(seconds = 0) {
  hours = Math.floor(seconds / 3600)
  minutes = Math.floor((seconds - hours * 3600) / 60)
  sec = Math.floor(seconds - hours * 3600 - minutes * 60)
  return minutes.toString().padStart(2, '0') + ':' + sec.toString().padStart(2, '0')
}

function initPlayer() {
  isPlaying.value = false
  isShowCapture.value = true
  isLoadingError.value = false
  duration.value = 0
  currentTime.value = 0
}

function clearTimer() {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

function startTimer() {
  clearTimer()
  timer = setInterval(() => (currentTime.value = player.value.currentTime), 1000)
}

function onAudioEnded() {
  isPlaying.value = false
  clearTimer()
  state.value.song = null
}

function onLoadMetaData() {
  if (player.value) {
    player.value.volume = volume.value
    duration.value = player.value.duration
    currentTime.value = player.value.currentTime
  }
}

function changeVolume(dir: 'down' | 'up') {
  if ((dir === 'up' && volume.value < 1) || (dir === 'down' && volume.value > 0)) {
    state.value.volume =
      dir === 'up' ? Math.min(volume.value + 0.1, 1) : Math.max(volume.value - 0.1, 0)
  }
}

onMounted(initPlayer)

watch(isPlaying, (isRun) =>
  isRun
    ? player.value
        .play()
        .then(startTimer)
        .catch(() => {
          isLoadingError.value = true
          isPlaying.value = false
          state.value.song = null
        })
    : player.value.pause(),
)

watch(volume, () => (player.value.volume = volume.value))

watch(() => state.value.song, initPlayer)
</script>

<template>
  <Teleport to="body">
    <OpacitySlideTopTransition>
      <div
        v-if="!isLoadingError && state.song && state.singer"
        @dblclick="isShowCapture = !isShowCapture"
        class="fixed bottom-0 left-0 w-full z-50"
      >
        <!-- Control pane -->
        <div
          class="grid grid-cols-1 px-8 h-28 bg-white border-t border-gray-200 md:grid-cols-3 dark:bg-gray-700 dark:border-gray-600"
        >
          <audio
            ref="player"
            preload="auto"
            :src="state.song.audio_url"
            @ended="onAudioEnded"
            @pause="clearTimer"
            @loadedmetadata="onLoadMetaData"
          />

          <div class="items-center justify-center hidden me-auto md:flex">
            <img
              class="h-12 me-3 rounded"
              :src="state.singer.poster_url"
              :alt="state.singer.name"
            />
            <span class="text-sm text-gray-500 dark:text-gray-400" v-text="state.singer.name" />
          </div>
          <div class="flex items-center w-full">
            <div class="w-full">
              <div class="flex items-center justify-center mx-auto mb-1">
                <Tooltip>
                  <template #trigger>
                    <button
                      @click="isPlaying = !isPlaying"
                      type="button"
                      class="inline-flex items-center justify-center p-2.5 mx-2 font-medium bg-blue-600 rounded-full hover:bg-blue-700 group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                    >
                      <Icon
                        v-if="isPlaying"
                        :icon="['fas', 'pause']"
                        class="w-3 h-3 md:w-4 md:h-4 text-white"
                      />
                      <Icon
                        v-else
                        :icon="['fas', 'play']"
                        class="ml-0.5 w-3 h-3 md:w-4 md:h-4 text-white"
                      />
                      <span class="sr-only" v-text="isPlaying ? 'Пауза' : 'Продолжить'" />
                    </button>
                  </template>
                  <template #content>{{ isPlaying ? 'Пауза' : 'Продолжить' }}</template>
                </Tooltip>
              </div>

              <FwbP class="text-center text-xs mt-2 mb-1" v-text="state.song.name" />

              <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                <span
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                  v-text="formatTime(currentTime)"
                />
                <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-800">
                  <div
                    class="bg-blue-600 h-1.5 rounded-full"
                    :style="{ width: `${(currentTime / duration) * 100}%` }"
                  />
                </div>
                <span
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                  v-text="formatTime(duration)"
                />
              </div>
            </div>
          </div>

          <div class="items-center justify-center hidden ms-auto md:flex">
            <Tooltip>
              <template #trigger>
                <button
                  @click="state.song = null"
                  type="button"
                  class="audio-player-button !py-1.5 group"
                >
                  <Icon :icon="['fas', 'circle-xmark']" class="audio-player-icon" />
                  <span class="sr-only">Закрыть</span>
                </button>
              </template>
              <template #content>Закрыть</template>
            </Tooltip>
            <Tooltip>
              <template #trigger>
                <Link
                  :href="/* @ts-ignore */ route('singer.show', state.singer.id)"
                  preserve-state
                  class="block audio-player-button group"
                >
                  <svg
                    class="audio-player-icon"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 18 16"
                  >
                    <path
                      d="M14.316.051A1 1 0 0 0 13 1v8.473A4.49 4.49 0 0 0 11 9c-2.206 0-4 1.525-4 3.4s1.794 3.4 4 3.4 4-1.526 4-3.4a2.945 2.945 0 0 0-.067-.566c.041-.107.064-.22.067-.334V2.763A2.974 2.974 0 0 1 16 5a1 1 0 0 0 2 0C18 1.322 14.467.1 14.316.051ZM10 3H1a1 1 0 0 1 0-2h9a1 1 0 1 1 0 2Z"
                    />
                    <path
                      d="M10 7H1a1 1 0 0 1 0-2h9a1 1 0 1 1 0 2Zm-5 4H1a1 1 0 0 1 0-2h4a1 1 0 1 1 0 2Z"
                    />
                  </svg>
                  <span class="sr-only">Отобразить плейлист</span>
                </Link>
              </template>
              <template #content>Отобразить плейлист</template>
            </Tooltip>

            <Tooltip>
              <template #trigger>
                <button
                  @click="player.currentTime = 0"
                  type="button"
                  class="audio-player-button group"
                >
                  <svg
                    class="audio-player-icon"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 18 20"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 1v5h-5M2 19v-5h5m10-4a8 8 0 0 1-14.947 3.97M1 10a8 8 0 0 1 14.947-3.97"
                    />
                  </svg>
                  <span class="sr-only">Начать заново</span>
                </button>
              </template>
              <template #content>Начать заново</template>
            </Tooltip>
            <Tooltip>
              <template #trigger>
                <button
                  @click="isShowCapture = !isShowCapture"
                  type="button"
                  class="audio-player-button group"
                >
                  <svg
                    class="audio-player-icon"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 16"
                  >
                    <path
                      d="M18 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM7.648 9.636c.25 0 .498-.064.717-.186a1 1 0 1 1 .979 1.745 3.475 3.475 0 1 1 .185-5.955 1 1 0 1 1-1.082 1.681 1.475 1.475 0 1 0-.799 2.715Zm6.186 0c.252 0 .5-.063.72-.187a1 1 0 1 1 .974 1.746 3.475 3.475 0 1 1 .188-5.955 1 1 0 0 1-1.084 1.681 1.475 1.475 0 1 0-.8 2.715h.002Z"
                    />
                  </svg>
                  <span class="sr-only">Текст песни</span>
                </button>
              </template>
              <template #content>Текст песни</template>
            </Tooltip>

            <!-- Volume -->
            <div
              @mouseenter="isShowVolumePopover = true"
              @mouseleave="isShowVolumePopover = false"
              @wheel="changeVolume(($event as WheelEvent).deltaY < 0 ? 'up' : 'down')"
              class="relative"
            >
              <OpacitySmallTransition>
                <div
                  v-show="isShowVolumePopover"
                  class="absolute -left-full -top-full -translate-y-full -translate-x-3 -mt-3 flex py-3 px-5 rounded-xl bg-gray-100 dark:bg-gray-800 -rotate-90"
                >
                  <FwbRange
                    v-model.number="state.volume"
                    :steps="0.1"
                    :max="1"
                    :value="0.5"
                    size="sm"
                    label=""
                    class="h-5 w-24"
                  />
                </div>
              </OpacitySmallTransition>
              <button
                type="button"
                class="p-2.5 group rounded-full hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600 dark:hover:bg-gray-600"
              >
                <svg
                  v-if="volume > 0"
                  class="audio-player-icon"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 20 18"
                >
                  <path
                    d="M10.836.357a1.978 1.978 0 0 0-2.138.3L3.63 5H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h1.63l5.07 4.344a1.985 1.985 0 0 0 2.142.299A1.98 1.98 0 0 0 12 15.826V2.174A1.98 1.98 0 0 0 10.836.357Zm2.728 4.695a1.001 1.001 0 0 0-.29 1.385 4.887 4.887 0 0 1 0 5.126 1 1 0 0 0 1.674 1.095A6.645 6.645 0 0 0 16 9a6.65 6.65 0 0 0-1.052-3.658 1 1 0 0 0-1.384-.29Zm4.441-2.904a1 1 0 0 0-1.664 1.11A10.429 10.429 0 0 1 18 9a10.465 10.465 0 0 1-1.614 5.675 1 1 0 1 0 1.674 1.095A12.325 12.325 0 0 0 20 9a12.457 12.457 0 0 0-1.995-6.852Z"
                  />
                </svg>
                <svg
                  v-else
                  class="audio-player-icon"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 20 18"
                >
                  <g>
                    <path
                      d="M10.836 0.357017C10.4889 0.194504 10.102 0.136342 9.72246 0.189601C9.3429 0.24286 8.98699 0.405247 8.698 0.657017L3.63 5.00002H2C1.46957 5.00002 0.960859 5.21073 0.585786 5.5858C0.210714 5.96088 0 6.46958 0 7.00002L0 11C0 11.5304 0.210714 12.0392 0.585786 12.4142C0.960859 12.7893 1.46957 13 2 13H3.63L8.7 17.344C8.9899 17.5956 9.34653 17.7578 9.7267 17.8109C10.1069 17.8639 10.4943 17.8056 10.842 17.643C11.1898 17.4843 11.4843 17.2283 11.6898 16.9058C11.8953 16.5834 12.003 16.2084 12 15.826V2.17402C12.0025 1.79102 11.8939 1.41552 11.6873 1.09302C11.4807 0.770513 11.185 0.514853 10.836 0.357017Z"
                    />
                    <path d="M14 7L18 11" stroke="currentColor" stroke-linecap="round" />
                    <path d="M18 7L14 11" stroke="currentColor" stroke-linecap="round" />
                  </g>
                </svg>

                <span class="sr-only">Изменить громкость</span>
              </button>
            </div>
          </div>
        </div>

        <FadeHeightTransition>
          <!--  Capture  -->
          <div v-show="isShowCapture" class="bg-white border-t border-gray-200">
            <div
              class="max-h-72 pt-4 pb-6 overflow-y-scroll text-center"
              v-html="state.song?.lyrics"
            />
          </div>
        </FadeHeightTransition>
      </div>
    </OpacitySlideTopTransition>
  </Teleport>
</template>

<style scoped>
.audio-player-icon {
  @apply w-4 h-4 text-gray-500 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white;
}
.audio-player-button {
  @apply p-2.5 rounded-full hover:bg-gray-100 me-1 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600 dark:hover:bg-gray-600;
}
</style>
