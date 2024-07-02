interface Discussion {
  discussion: { comments: [] }
}

export async function getCommentsById(id: number) {
  const { data } = await axios.get<Discussion>(route('discussion.show', { id }))
  return data
}
