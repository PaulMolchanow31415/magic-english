interface Array<T> {
  shuffle: () => Array<T>;
}

Array.prototype.shuffle = function () {
  return this.sort(() => Math.random() - 0.5)
}
