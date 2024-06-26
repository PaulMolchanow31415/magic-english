export default (name = '_') => {
  const arr = name.trim().split(' ')
  const s = arr[0].charAt(0)
  return (arr.length > 1 ? s + arr[1].charAt(0) : s).toUpperCase()
}
