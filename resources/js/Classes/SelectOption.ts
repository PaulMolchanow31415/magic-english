import ValidationError from './ValidationError'
import { capitalize } from 'vue'

interface ISelectOption {
  value: string
  name: string
}

export default class implements ISelectOption {
  value: string
  name: string

  constructor(props: ISelectOption) {
    this.value = props.value
    this.name = capitalize(props.name)

    if (!this.validate()) {
      console.error(this)
      throw new ValidationError()
    }
  }

  private validate() {
    return typeof this.name === 'string' && typeof this.value === 'string'
  }
}
