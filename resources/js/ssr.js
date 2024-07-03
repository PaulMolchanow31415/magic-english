import './Core/bootstrap'

import createServer from '@inertiajs/vue3/server'
import { createInertiaApp } from '@inertiajs/vue3'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist'

import { appTitle, inertiaProgressSettings, layoutResolver } from './Core'
import { ConfiguratorPlugin } from '@/Plugins'

createServer((page) =>
  createInertiaApp({
    page,
    title: appTitle,
    render: renderToString,
    resolve: layoutResolver,
    progress: inertiaProgressSettings,
    // prettier-ignore
    setup: ({ App, props, plugin }) =>
      createSSRApp({ render: () => h(App, props) })
      .use(ConfiguratorPlugin, [plugin], [
        ZiggyVue, {
          ...page.props.ziggy,
          location: new URL(page.props.ziggy.location),
        },
      ]),
  }),
)
