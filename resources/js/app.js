import './bootstrap'
import '../pcss/globals.pcss'

import '@/Libraries/font-awesome'
import '@/Libraries/quill.js'

import { createApp, h, warn } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { VueRecaptchaPlugin } from 'vue-recaptcha/head'

import BaseLayout from '@/Layouts/BaseLayout.vue'
import RecaptchaLayout from '@/Layouts/RecaptchaLayout.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { QuillEditor } from '@vueup/vue-quill'

import translatable from '@/Directives/translatable.js'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const appName = import.meta.env.VITE_APP_NAME
const captchaKey = import.meta.env.VITE_RECAPTCHA_KEY

await createInertiaApp({
  title: (title) => `${title} - ${appName}`,

  resolve(name) {
    const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
    page.then((module) => {
      module.default.layout = (function () {
        const { layout } = module.default

        if (name.startsWith('Admin')) {
          return [RecaptchaLayout, BaseLayout, AdminLayout]
        }
        if (!(layout instanceof Array)) {
          // layout is object or undefined
          return [RecaptchaLayout, layout || BaseLayout]
        }
        // check that layout[] contains RecaptchaLayout
        if (layout.find((value) => value.__name === RecaptchaLayout.__name)) {
          return layout
        }
        if (layout.length > 0) {
          // layout[] without RecaptchaLayout
          layout.unshift(RecaptchaLayout)
          return layout
        }

        warn('Вы записали пустой массив в опцию layout', layout)
        return [RecaptchaLayout, BaseLayout]
      })()
    })
    return page
  },

  setup: ({ el, App, props, plugin }) =>
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VueRecaptchaPlugin, { v3SiteKey: captchaKey })
      .component('Icon', FontAwesomeIcon)
      .component('HtmlEditor', QuillEditor)
      .directive('translatable', translatable)
      .mount(el),

  progress: {
    color: '#1c64f2',
  },
})
