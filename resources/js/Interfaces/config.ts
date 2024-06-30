import { DefineComponent } from 'vue'

export interface InertiaProgressOptions {
  delay?: number
  color?: string
  includeCSS?: boolean
  showSpinner?: boolean
}

export interface IModule {
  default: {
    layout: DefineComponent[] | DefineComponent
  }
}
