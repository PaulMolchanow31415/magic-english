import './bootstrap'

import '../pcss/globals.pcss'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import CKEditor from '@ckeditor/ckeditor5-vue'
import VWave from 'v-wave'

/* Configs */
import '@/Config/fontAwesome'
import { appTitle, inertiaProgressSettings, layoutResolver, vueReCaptchaSettings } from '@/Config'

createInertiaApp({
  title: appTitle,
  resolve: layoutResolver,
  progress: inertiaProgressSettings,

  setup: ({ el, App, props, plugin }) =>
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VueReCaptcha, vueReCaptchaSettings)
      .use(CKEditor)
      .use(VWave)
      .component('Icon', FontAwesomeIcon)
      .mount(el),
})
