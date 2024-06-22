<x-mail::message>
# {{ $title }}

Ваш текущий пароль:<br />
**{{ $password }}**

<x-mail::button :url="$actionUrl">{{ $actionText }}</x-mail::button>
</x-mail::message>
