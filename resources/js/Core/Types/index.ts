export interface IEntity {
  id: number
}

export interface ISuggestion {
  data: IEntity[]
}

export interface ISortableItem extends IEntity {
  name: string
}

export interface ISuggestListItem extends IEntity {
  value: string
}
