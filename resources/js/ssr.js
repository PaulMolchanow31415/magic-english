import './Plugins'

import createServer from '@inertiajs/vue3/server'
import { createInertiaApp } from '@inertiajs/vue3'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'

import { VueReCaptcha } from 'vue-recaptcha-v3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import CKEditor from '@ckeditor/ckeditor5-vue'
import VWave from 'v-wave'

import { appTitle, inertiaProgressSettings, layoutResolver, vueReCaptchaSettings } from './Core'

createServer((page) =>
  createInertiaApp({
    page,
    title: appTitle,
    render: renderToString,
    resolve: layoutResolver,
    progress: inertiaProgressSettings,

    setup: ({ App, props, plugin }) =>
      createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        })
        .use(VueReCaptcha, vueReCaptchaSettings)
        .use(CKEditor)
        .use(VWave)
        .component('Icon', FontAwesomeIcon),
  }),
)
