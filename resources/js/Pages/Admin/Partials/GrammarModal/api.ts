export async function loadGrammars(courseId: number) {
  const { data } = await axios.get<[]>(route('admin.grammar.show', { courseId }))
  return data
}
