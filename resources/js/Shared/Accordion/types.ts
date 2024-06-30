export const ACCORDION_INJECTION_KEY = Symbol('ACCORDION_INJECTION_KEY')

export interface AccordionProvidePayload {
  accordionId: string
  flush?: boolean
}
