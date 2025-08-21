<?php

namespace App\Enums;

enum ProductStatus: int
{
    case ACTIVE = 1;
    case PENDING = 2;
    case INACTIVE = 3;
}