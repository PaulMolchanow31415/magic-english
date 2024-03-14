import './bootstrap'
import './Libraries/font-awesome'

import '../pcss/globals.pcss'

import { createSSRApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import CKEditor from '@ckeditor/ckeditor5-vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { resolveLayout } from '@/resolveLayout.js'

const appName = import.meta.env.VITE_APP_NAME
const captchaKey = import.meta.env.VITE_RECAPTCHA_KEY

createInertiaApp({
  title: (title) => `${title} | ${appName}`,
  resolve: resolveLayout,
  progress: { color: '#1c64f2' },

  setup: ({ el, App, props, plugin }) =>
    createSSRApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VueReCaptcha, {
        siteKey: captchaKey,
        loaderOptions: { autoHideBadge: true },
      })
      .use(CKEditor)
      .component('Icon', FontAwesomeIcon)
      .mount(el),
}).catch(console.error)
