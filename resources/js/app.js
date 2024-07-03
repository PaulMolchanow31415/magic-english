import './Core/bootstrap'

import '../pcss/globals.pcss'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'

import { appTitle, inertiaProgressSettings, layoutResolver } from './Core'
import { ConfiguratorPlugin } from '@/Plugins'

createInertiaApp({
  title: appTitle,
  resolve: layoutResolver,
  progress: inertiaProgressSettings,

  setup: ({ el, App, props, plugin }) =>
    createApp({ render: () => h(App, props) })
      .use(ConfiguratorPlugin, [plugin], [ZiggyVue])
      .mount(el),
})
