<?php

namespace App\Models;

enum Complexity: string {
    case EASY   = 'Легкий';
    case MIDDLE = 'Средний';
    case HARD   = 'Сложный';
}
