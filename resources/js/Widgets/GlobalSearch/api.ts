import { SearchResult } from './types'

export async function search(query: string) {
  const { data } = await axios.get<SearchResult[]>(route('api.global-search'), {
    params: { query },
  })
  return data
}
