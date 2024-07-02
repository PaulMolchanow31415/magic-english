import { route as routeFn } from '../../../../vendor/tightenco/ziggy'
import { AxiosInstance } from 'axios'

declare global {
  interface Window {
    axios: AxiosInstance
    route: typeof routeFn
  }

  const axios: AxiosInstance
  const route: typeof routeFn
}
