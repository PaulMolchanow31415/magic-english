import ValidationError from './ValidationError'
import { TypeToast } from '../Types'

interface IToast {
  type: TypeToast
  isShow: boolean
  value: string
  closable?: boolean
}

export default class implements IToast {
  type: TypeToast
  isShow: boolean
  value: string
  closable = false

  constructor(props: IToast) {
    this.type = props.type
    this.isShow = props.isShow
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
      typeof this.isShow === 'boolean' &&
      this.value &&
      typeof this.value === 'string' &&
      this.value.length > 0
    )
  }
}
