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
  }
}
