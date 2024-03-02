<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description"
              content="Онлайн-самоучитель английского языка поможет вам поднять ваш уровень до нормального. Всего за месяц вы сможете свободно выражать свои мысли, понимать собеседников, смотреть фильмы на английском, участвовать в зарубежных форумах и переводить тексты песен. Начните учить английский прямо сейчас!"
        >
        <meta name="keywords"
              content="английский язык, онлайн-самоучитель, уровень английского, выражение мыслей, понимание собеседников, фильмы на английском, зарубежные форумы, перевод текстов песен."
        >
        <link rel="icon" href="{{ asset('favicon.svg') }}">

        <title inertia>{{ config('app.name') }}</title>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
