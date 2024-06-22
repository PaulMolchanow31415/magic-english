import './bootstrap'
import './Config/FontAwesome.js'

import '../pcss/globals.pcss'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import CKEditor from '@ckeditor/ckeditor5-vue'
import VWave from 'v-wave'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import layoutResolver from '@/Helpers/layoutResolver.js'
import appTitle from '@/Helpers/appTitle.js'
import VueReCaptchaSettings from '@/Config/VueReCaptchaSettings.js'
import InertiaProgress from '@/Config/InertiaProgress.js'

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
