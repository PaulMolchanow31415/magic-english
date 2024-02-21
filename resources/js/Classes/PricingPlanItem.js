import ValidationError from '@/Classes/ValidationError.js'

export default class {
  isAllowed
  description

  constructor({ isAllowed, description }) {
    this.isAllowed = isAllowed
    this.description = description

    if (!this.#validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  #validate() {
    return typeof this.isAllowed === 'boolean' && typeof this.description === 'string'
  }
}