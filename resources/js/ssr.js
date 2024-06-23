import './Config/FontAwesome'
import '@/Helpers/Extensions'

import createServer from '@inertiajs/vue3/server'
import { createInertiaApp } from '@inertiajs/vue3'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import CKEditor from '@ckeditor/ckeditor5-vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import VWave from 'v-wave'

import layoutResolver from '@/Helpers/layoutResolver'
import appTitle from '@/Helpers/appTitle'
import VueReCaptchaSettings from '@/Config/VueReCaptchaSettings'
import InertiaProgress from '@/Config/InertiaProgress'

createServer((page) =>
  createInertiaApp({
    page,
    title: appTitle,
    render: renderToString,
    resolve: layoutResolver,
    progress: InertiaProgress,

    setup: ({ App, props, plugin }) =>
      createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        })
        .use(VueReCaptcha, VueReCaptchaSettings)
        .use(CKEditor)
        .use(VWave)
        .component('Icon', FontAwesomeIcon),
  }),
)
