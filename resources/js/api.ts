import { RouteName } from './Types'
import { ISuggestion } from './Interfaces'

export async function apiTranslate(word: string) {
  const { data } = await axios.get<string[]>(route('api.translate', { word }))
  return data
}

export async function getSuggestions(name: RouteName, search: string) {
  const { data } = await axios.get<ISuggestion>(route(name, { search }))
  return data
}
