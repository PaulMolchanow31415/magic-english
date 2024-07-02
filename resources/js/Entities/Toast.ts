import ValidationError from './ValidationError'

type TypeToast = 'success' | 'info' | 'warning'

interface IToast {
  type: TypeToast
  value: string
  closable?: boolean
}

export default class implements IToast {
  type: TypeToast
  value: string
  closable = false

  constructor(props: IToast) {
    this.type = props.type
    this.value = props.value
    this.closable = props.closable ?? false

    if (!this.validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  private validate() {
    return (
      this.type &&
      (this.type === 'success' || this.type === 'info' || this.type === 'warning') &&
      this.value &&
      typeof this.value === 'string' &&
      this.value.length > 0
    )
  }
}
