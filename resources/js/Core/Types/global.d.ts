import { route as routeFn } from '../../../../vendor/tightenco/ziggy'
import { AxiosInstance } from 'axios'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { Helpers } from '../../Plugins'

declare global {
  interface Window {
    axios: AxiosInstance
    route: typeof routeFn
  }

  const axios: AxiosInstance
  const route: typeof routeFn
  static const $helpers: Helpers

  declare module 'vue' {
    export interface ComponentCustomProperties {
      $helpers: Helpers
    }
  }

  declare module '@vue/runtime-core' {
    export interface GlobalComponents {
      Icon: typeof FontAwesomeIcon
    }
  }
}

