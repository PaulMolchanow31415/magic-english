import ValidationError from '@/Classes/ValidationError.js'

export default class SuggestListItem {
  id
  value

  constructor({ id, value }) {
    this.id = id
    this.value = value

    if (!this.#validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  #validate() {
    return typeof this.id === 'number' && typeof this.value === 'string'
  }
}
