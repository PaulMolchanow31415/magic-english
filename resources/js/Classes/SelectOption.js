import ValidationError from '@/Classes/ValidationError.js'
import { capitalize } from 'vue'

export default class SelectOption {
  value
  name

  constructor({ value, name }) {
    this.value = value
    this.name = capitalize(name)

    if (!this.#validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  #validate() {
    return typeof this.name === 'string' && typeof this.value === 'string'
  }
}
