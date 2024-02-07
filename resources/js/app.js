import './bootstrap'
import '../pcss/globals.pcss'

import '@/Libraries/font-awesome'

import { createApp, h, warn } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { VueRecaptchaPlugin } from 'vue-recaptcha/head'
import CKEditor from '@ckeditor/ckeditor5-vue'

import BaseLayout from '@/Layouts/BaseLayout.vue'
import RecaptchaLayout from '@/Layouts/RecaptchaLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import translatable from '@/Directives/translatable.js'

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
          return [RecaptchaLayout, layout || BaseLayout]
        }

        if (layout.find((component) => component.__name === RecaptchaLayout.__name)) {
          return layout
        }

        if (layout.length > 0) {
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
      .use(CKEditor)
      .component('Icon', FontAwesomeIcon)
      .directive('translatable', translatable)
      .mount(el),

  progress: {
    color: '#1c64f2',
  },
})
