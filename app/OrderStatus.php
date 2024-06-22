<?php

namespace App;

enum OrderStatus: string {
    case INCOMPLETE = 'incomplete';
    case COMPLETED  = 'completed';
}
