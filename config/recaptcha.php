<?php

return [
    'url'      => 'https://www.google.com/recaptcha/api/siteverify',
    'site_key' => env('VITE_RECAPTCHA_KEY'),
    'secret'   => env('RECAPTCHA_SECRET_KEY'),
];