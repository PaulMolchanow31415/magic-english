import { Ref } from 'vue'

export const ACCORDION_INJECTION_KEY = Symbol('ACCORDION_INJECTION_KEY')

export type AccordionProps = {
  alwaysOpen?: boolean
  openFirst?: boolean
  flush?: boolean
}

export interface AccordionProviderPayload extends AccordionProps {
  accordionId: string
  openedItemId?: Ref<string>
}
