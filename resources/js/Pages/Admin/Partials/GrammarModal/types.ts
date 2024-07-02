export interface GrammarRule {
  order: number
  id: number
  course_id: number
  title: string
  content: string
  phonetics: {
    source: string
    translation: string
  }[]
  source: string
}
