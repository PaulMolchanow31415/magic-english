interface Math {
  randomInt: (min: number, max: number) => number;
}

Math.randomInt = function (min, max) {
  const minCeiled = Math.ceil(min)
  const maxFloored = Math.floor(max)
  return Math.floor(Math.random() * (maxFloored - minCeiled + 1) + minCeiled)
}