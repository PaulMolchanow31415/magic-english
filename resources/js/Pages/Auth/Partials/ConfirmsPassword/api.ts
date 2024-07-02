export const getPasswordConfirmStatus = () => axios.get(route('password.confirmation'))
export const doConfirmPassword = (password: string) =>
  axios.post(route('password.confirm'), { password })
