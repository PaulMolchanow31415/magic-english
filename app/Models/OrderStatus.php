<?php

namespace App\Models;

enum OrderStatus: string {
    case INCOMPLETE = 'incomplete';
    case COMPLETED  = 'completed';
}
