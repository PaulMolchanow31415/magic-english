import { IEntity } from './api'

export * from './config'
export * from './api'

export interface ISortableItem extends IEntity {
  name: string
}

export interface ISuggestListItem extends IEntity {
  value: string
}
