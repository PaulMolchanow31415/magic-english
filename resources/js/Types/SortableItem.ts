import ValidationError from './ValidationError'

interface ISortableItem {
  id: number
  name: string
}

export default class implements ISortableItem {
  id: number
  name: string

  constructor(props: ISortableItem) {
    this.id = props.id
    this.name = props.name

    if (!this.validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  private validate() {
    return typeof this.id === 'number' && typeof this.name === 'string'
  }
}
