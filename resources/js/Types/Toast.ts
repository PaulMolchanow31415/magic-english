import ValidationError from './ValidationError'

type ToastType = 'success' | 'info' | 'warning'

interface IToast {
  type: ToastType
  isShow: boolean
  value: string
}

export default class implements IToast {
  type: ToastType
  isShow: boolean
  value: string

  constructor(props: IToast) {
    this.type = props.type
    this.isShow = props.isShow
    this.value = props.value

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
