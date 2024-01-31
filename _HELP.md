# Apis

<https://mymemory.translated.net/doc/spec.php>

## Frameworks

_creates migration, rest controller and factory_
php artisan make:model Example -mrcf
_generate the model commands_
<https://laravel.com/docs/10.x/eloquent#generating-model-classes>
_seeder commands_
<https://laravel.com/docs/10.x/seeding#running-seeders>
_animations_
<https://gsap.com/docs/v3/Installation/>
<https://inertiajs.com/authorization>
<https://inertiajs.com/error-handling>
<https://laravel.com/docs/10.x/eloquent-mutators#array-object-and-collection-casting>
**database**
<https://laravel.com/docs/10.x/migrations#rolling-back-migrations>
**define default language in browser**
<https://vueuse.org/core/usePreferredLanguages/#usepreferredlanguages>
**share button**
<https://vueuse.org/core/useShare/#useshare>
**vue 3 notification**
<https://vueuse.org/core/useWebNotification/#usewebnotification>
**listening keystroke**
<https://vueuse.org/core/onKeyStroke/#onkeystroke>
**focusing input**
<https://vueuse.org/core/useFocus/#usefocus>

## Components

- For course

<http://begin-english.ru/>
**table**
<https://vueup.github.io/vue-quill/guide/modules.html#quill-modules>
<https://flowbite.com/docs/components/breadcrumb/#solid-background>
<https://flowbite-vue.com/components/radio>
<https://flowbite-vue.com/components/card>
<https://flowbite.com/blocks/publisher/blog-templates/>
<https://flowbite-vue.com/components/tooltip#vue-tooltip-flowbite>
<https://flowbite-vue.com/components/progress>
<https://vueuse.org/core/useSpeechSynthesis/#usespeechsynthesis>
**course in process**
<https://flowbite-vue.com/components/timeline#default-timeline-usage>
**infinite scroll**
<https://vueuse.org/core/useInfiniteScroll/#useinfinitescroll>

- For music

<https://vueuse.org/core/useMemory/#usememory>
<https://github.com/vueuse/sound#examples>
<https://flowbite.com/docs/components/bottom-navigation/#video-player-bar>

- For comments

<https://flowbite.com/blocks/publisher/comments/#default-comments-list>
<https://flowbite-vue.com/components/textarea#comment-box>
**delete comment**
<https://flowbite.com/blocks/marketing/popup/>

- For user profile

<https://flowbite-vue.com/components/toggle>

- For admin panel

<https://flowbite-vue.com/components/tooltip#vue-tooltip-flowbite>
<https://flowbite-vue.com/components/badge#>
<https://flowbite-vue.com/components/sidebar#content-separator>
**CRUD**
<https://flowbite-vue.com/components/fileInput#dropone>
<https://flowbite-vue.com/components/modal#vue-modal-flowbite>
<https://flowbite.com/blocks/application/table-headers/>
<https://flowbite-vue.com/components/table#hoverable-example>
<https://flowbite.com/blocks/application/crud-update-modals/>
<https://flowbite.com/blocks/application/crud-delete-confirm/>

- For user status in network

<https://flowbite-vue.com/components/avatar#dot-indicator>

- 429 Too many attempts

- 403 Access denied

- 404

<https://flowbite.com/blocks/marketing/404/#default-example>

- 500

<https://flowbite.com/blocks/marketing/500/#default-500-page>

## Localization

<https://dev.to/paulocastellano/how-to-work-with-laravel-inertia-js-and-vue-i18n-26ih>
<https://kazupon.github.io/vue-i18n/guide/formatting.html#named-formatting>

## Diploma docs helpers

<https://dev.mysql.com/doc/refman/8.0/en/storage-requirements.html>

## Short keys in program

- Shift + N - добавление, файл (TableHeader)
- ArrowLeft, ArrowRight - навигация в админ панели, файл (Pagination)
- Shift + Enter - подтверждение сохранения, файл (UpdateModal)
- Ctrl + K - быстрый поиск, файл (SearchInput)
- 2 лкм - редактирование категории лексики, файл (VocabularyCategory)
- Ctrl + D - Удаление существующей категории лексики (VocabularyCategory)

## Testing

<https://laravel.com/docs/10.x/testing>

<div class="grid gap-4 mb-4 sm:grid-cols-2">
  <div>
    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
      >Name</label
    >
    <input
      type="text"
      name="name"
      id="name"
      value="iPad Air Gen 5th Wi-Fi"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
      placeholder="Ex. Apple iMac 27&ldquo;"
    />
  </div>
  <div>
    <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
      >Brand</label
    >
    <input
      type="text"
      name="brand"
      id="brand"
      value="Google"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
      placeholder="Ex. Apple"
    />
  </div>
  <div>
    <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
      >Price</label
    >
    <input
      type="number"
      value="399"
      name="price"
      id="price"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
      placeholder="$299"
    />
  </div>
  <div>
    <label
      for="category"
      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
      >Category</label
    >
    <select
      id="category"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
    >
      <option selected="">Electronics</option>
      <option value="TV">TV/Monitors</option>
      <option value="PC">PC</option>
      <option value="GA">Gaming/Console</option>
      <option value="PH">Phones</option>
    </select>
  </div>
  <div class="sm:col-span-2">
    <label
      for="description"
      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
      >Description</label
    >
    <textarea
      id="description"
      rows="5"
      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
      placeholder="Write a description..."
    >
Standard glass, 3.8 GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory, Radeon Pro 5500 XT with 8GB of GDDR6 memory, 256GB SSD storage, Gigabit Ethernet, Magic Mouse 2, Magic Keyboard - US</textarea
    >
  </div>
</div>
