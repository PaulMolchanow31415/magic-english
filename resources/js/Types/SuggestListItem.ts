import ValidationError from './ValidationError'

interface ISuggestListItem {
  id: number
  value: string
}

export default class implements ISuggestListItem {
  id: number
  value: string

  constructor(props: ISuggestListItem) {
    this.id = props.id
    this.value = props.value

    if (!this.validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  private validate() {
    return typeof this.id === 'number' && typeof this.value === 'string'
  }
}
