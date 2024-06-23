import './bootstrap'
import './Config/FontAwesome'
import '@/Helpers/Extensions'

import '../pcss/globals.pcss'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import CKEditor from '@ckeditor/ckeditor5-vue'
import VWave from 'v-wave'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import layoutResolver from '@/Helpers/layoutResolver'
import appTitle from '@/Helpers/appTitle'
import VueReCaptchaSettings from '@/Config/VueReCaptchaSettings'
import InertiaProgress from '@/Config/InertiaProgress'

createInertiaApp({
  title: appTitle,
  resolve: layoutResolver,
  progress: InertiaProgress,

  setup: ({ el, App, props, plugin }) =>
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VueReCaptcha, VueReCaptchaSettings)
      .use(CKEditor)
      .use(VWave)
      .component('Icon', FontAwesomeIcon)
      .mount(el),
})
