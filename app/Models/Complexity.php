<?php

namespace App\Models;

enum Complexity: string {
    case EASY   = 'легкий';
    case MIDDLE = 'средний';
    case HARD   = 'сложный';
}
