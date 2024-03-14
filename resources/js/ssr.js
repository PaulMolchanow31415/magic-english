import './Libraries/font-awesome'

import '../pcss/globals.pcss'

import createServer from '@inertiajs/vue3/server'
import { createInertiaApp } from '@inertiajs/vue3'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import CKEditor from '@ckeditor/ckeditor5-vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { resolveLayout } from '@/resolveLayout.js'

const appName = import.meta.env.VITE_APP_NAME
const captchaKey = import.meta.env.VITE_RECAPTCHA_KEY

createServer((page) =>
  createInertiaApp({
    title: (title) => `${title} | ${appName}`,
    page,
    render: renderToString,
    resolve: resolveLayout,
    progress: { color: '#1c64f2' },

    setup: ({ App, props, plugin }) =>
      createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        })
        .use(VueReCaptcha, {
          siteKey: captchaKey,
          loaderOptions: { autoHideBadge: true },
        })
        .use(CKEditor)
        .component('Icon', FontAwesomeIcon),
  }),
)
