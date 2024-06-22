export default (name = '_') => {
  name = name.trim().split(' ')
  return (
    name.length > 1 ? name[0].charAt(0).concat(name[1].charAt(0)) : name[0].charAt(0)
  ).toUpperCase()
}
