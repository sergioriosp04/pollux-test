<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 1;
    case IN_PROCESS = 2;
    case DELIVERED = 3;
    case CANCELED = 4;
}