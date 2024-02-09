import ValidationError from '@/Classes/ValidationError.js'

export default class Toast {
  type
  isShow
  value

  constructor({ type, isShow, value }) {
    this.type = type
    this.isShow = isShow
    this.value = value

    if (!this.validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  validate() {
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
