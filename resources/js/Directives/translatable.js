import TranslationPopover from '@/Classes/TranslationPopover.js'

let popover
let $root

function clickOutside(target) {
  let isOutside = true
  let parent = target.parentNode

  while (isOutside) {
    if (!parent) {
      return true
    }

    isOutside = parent !== $root
    parent = parent.parentNode
  }
  return isOutside
}

function clickPopover(target) {
  let isClicked = false
  let parent = target.parentNode

  while (!isClicked) {
    if (!parent) {
      return false
    }

    isClicked = parent === popover.$el && popover.isShow
    parent = parent.parentNode
  }
  return isClicked
}

async function handleClick(event) {
  if (clickPopover(event.target)) {
    return
  }

  if (clickOutside(event.target)) {
    popover.hide()
    return
  }

  const selection = getSelection()
  const range = selection.getRangeAt(0)
  const text = selection.anchorNode.textContent
  let word
  let startIndex
  let endIndex
  let currentIndex = range.startOffset

  while (typeof startIndex !== 'number') {
    if (currentIndex === -1) {
      startIndex = 0
    } else if (text[--currentIndex] === ' ') {
      startIndex = currentIndex + 1
    }
  }

  currentIndex = range.startOffset

  while (!endIndex) {
    if (currentIndex + 1 === text.length) {
      endIndex = text.length
    } else if (text[++currentIndex] === ' ') {
      endIndex = currentIndex
    }
  }

  word = text.slice(startIndex, endIndex).replaceAll(/[^a-zA-Z\s]+/g, '')

  console.log('translatable', word)

  if (!word || word.length === 0) {
    return
  }

  const res = await axios.get(route('api.translate', { word }))

  popover.show(
    {
      x: event.clientX,
      y: event.clientY,
    },
    {
      srcWord: word,
      // сортировка по убыванию качества преревода - 3 строки в нижнем регистре
      translated: res.data
        .sort((a, b) => b.quality - a.quality)
        .filter((t) => t.translation.match(/^[А-я\s]+$/)) // todo - сделать это на сервере!!!
        .slice(0, 3)
        .map((t) => t.translation.trim().toLowerCase()),
    },
  )
}

export default {
  mounted(el) {
    $root = el
    popover = new TranslationPopover(el)
    document.addEventListener('click', handleClick)
  },
  beforeUnmount(el) {
    $root = null
    popover.destroy(el)
    document.removeEventListener('click', handleClick)
  },
}