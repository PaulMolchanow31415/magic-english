<?php

namespace App;

enum Role: string {
    case ADMIN = 'Администратор';
    case USER  = 'Пользователь';
}
