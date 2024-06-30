import { FwbBadge, FwbInput, FwbSelect, FwbToast } from 'flowbite-vue'

export type TypeToast = 'success' | 'info' | 'warning'
export type TypeTheme = 'light' | 'dark'

// external
export type FwbToastType = typeof FwbToast.__defaults.type
export type FwbValidationStatus = typeof FwbInput.__defaults.validationStatus
export type FwbBadgeSize = typeof FwbBadge.__defaults.size
export type FwbBadgeType = typeof FwbBadge.__defaults.type
export type FwbOptionsType = typeof FwbSelect.__defaults.options

export { type RouteName } from '../../../vendor/tightenco/ziggy'
