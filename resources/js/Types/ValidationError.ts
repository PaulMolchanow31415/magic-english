export default class ValidationError extends Error {
  constructor() {
    super('Ошибка валидации входных параметров')
  }
}
