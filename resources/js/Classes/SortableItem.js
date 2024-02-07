import ValidationError from '@/Classes/ValidationError.js'

export default class SortableItem {
  id
  name

  constructor({ id, name }) {
    this.id = id
    this.name = name

    if (!this.#validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  #validate() {
    return typeof this.id === 'number' && typeof this.name === 'string'
  }
}