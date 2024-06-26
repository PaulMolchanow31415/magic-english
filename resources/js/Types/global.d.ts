// import { Router } from 'vendor/tightenco/ziggy/src/js/Router.js'
import { route as routeFn } from '../../../vendor/tightenco/ziggy/src/js/index.d.ts'
import { AxiosInstance } from 'axios'

declare global {
  interface Window {
    axios: AxiosInstance
    route: typeof routeFn
  }

  const axios: AxiosInstance
  const route: typeof routeFn
}
