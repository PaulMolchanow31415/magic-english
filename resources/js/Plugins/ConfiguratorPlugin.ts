import { App, Plugin } from 'vue'

import { VueReCaptcha } from 'vue-recaptcha-v3'
import { vueReCaptchaSettings } from '../Core'
import CKEditor from '@ckeditor/ckeditor5-vue'
import VWave from 'v-wave'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { HelpersPlugin } from './HelpersPlugin'

type ConfiguratorOptions = [Plugin, any | undefined][]

/**
 * installs plugins by default and creates $helpers instance
 * */
export const ConfiguratorPlugin: Plugin<ConfiguratorOptions> = {
  install(app: App, ...plugins: ConfiguratorOptions) {
    plugins.forEach((plugin) => app.use(plugin[0], plugin[1]))

    app
      .use(VueReCaptcha, vueReCaptchaSettings)
      .use(CKEditor)
      .use(VWave, {})
      .use(HelpersPlugin)
      .component('Icon', FontAwesomeIcon)
  },
}
