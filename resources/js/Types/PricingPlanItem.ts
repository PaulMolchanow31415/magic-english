import ValidationError from './ValidationError'

interface IPricingPlanItem {
  isAllowed: boolean
  description: string
}

export default class implements IPricingPlanItem {
  isAllowed: boolean
  description: string

  constructor(props: IPricingPlanItem) {
    this.isAllowed = props.isAllowed
    this.description = props.description

    if (!this.validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  private validate() {
    return typeof this.isAllowed === 'boolean' && typeof this.description === 'string'
  }
}
