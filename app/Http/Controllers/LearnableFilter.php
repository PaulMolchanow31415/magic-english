<?php

namespace App\Http\Controllers;

enum LearnableFilter: string {
    case ALL            = 'Все';
    case ONLY_COMPLETED = 'Только изученные';
    case ONLY_STUDIED   = 'Изучаемые';
}
