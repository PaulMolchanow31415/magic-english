export const ACCORDION_INJECTION_KEY = Symbol('ACCORDION_INJECTION_KEY')

export interface AccordionProviderPayload {
  accordionId: string
  flush?: boolean
}
