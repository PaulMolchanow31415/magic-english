export async function translate(word: string) {
  const { data } = await axios.get<string[]>(route('api.translate', { word }))
  return data
}
