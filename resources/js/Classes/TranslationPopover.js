export default class TranslationPopover {
  $el
  isShow = false
  srcWord
  translated
  $learnBtn
  $closeBtn

  constructor(wrapper) {
    this.$el = document.createElement('div')
    this.$el.classList.add('hidden', 'absolute')
    this.$el.style.transform = 'translate(-50%, calc(-100% - 1rem))'
    wrapper.appendChild(this.$el)
  }

  show({ x, y }, { srcWord, translated }) {
    if (translated.length === 0 || !srcWord) {
      return
    }

    this.srcWord = srcWord
    this.translated = translated

    const translations = translated.map((t) => `<li>${t}</li>`).join('\n')
    const uuid = crypto.randomUUID()

    this.$el.innerHTML = `
      <div id="${uuid}"
           role="tooltip"
           class="inline-block w-64 text-sm text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800"
      >
          <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
              <div class="flex justify-between items-center">
                  <h3 class="font-semibold text-gray-900 dark:text-white">${srcWord}</h3>
                  <!-- Close button -->
                  <button type="button" class="shrink-0" id="${uuid}-close-btn">
                      <svg class="fill-gray-900 dark:fill-white"
                           xmlns="http://www.w3.org/2000/svg"
                           height="16"
                           width="16"
                           viewBox="0 0 512 512"
                      ><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z" /></svg>
                  </button>
              </div>
          </div>
          <div class="px-3 py-2">
              <ul class="mb-2 leading-normal list-disc pl-6">${translations}</ul>
              <!-- Learn button -->
              <button id="${uuid}-learn-btn"
                      type="button"
                      class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg w-full hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
              >
                Выучить
              </button>
          </div>
      </div>
    `

    this.$learnBtn = document.getElementById(`${uuid}-learn-btn`)
    this.$closeBtn = document.getElementById(`${uuid}-close-btn`)

    this.$learnBtn.addEventListener('click', () => this.#handleLearn())
    this.$closeBtn.addEventListener('click', (e) => this.hide(e))

    this.$el.style.left = x + 'px'
    this.$el.style.top = y + 'px'
    this.$el.classList.remove('hidden')
    this.isShow = true
  }

  #handleLearn() {
    axios
      .post(
        route('learn.add-word', {
          srcWord: this.srcWord,
          translated: this.translated,
        }),
      )
      .finally(this.hide)
  }

  hide(event) {
    try {
      event.stopPropagation()
    } catch (e) {}
    this.$el.classList.add('hidden')
    this.isShow = false
  }

  destroy(wrapper) {
    wrapper.removeChild(this.$el)
    delete this.isShow
    delete this.srcWord
    delete this.translated
    delete this.$learnBtn
    delete this.$closeBtn
    delete this.$el
  }
}
