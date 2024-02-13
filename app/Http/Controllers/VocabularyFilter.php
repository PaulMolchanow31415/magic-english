<?php

namespace App\Http\Controllers;

enum VocabularyFilter: string {
    case ALL            = 'Все';
    case ONLY_COMPLETED = 'Только изученные';
    case ONLY_STUDIED   = 'Изучаемые';
}
