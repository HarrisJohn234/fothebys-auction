<?php

namespace App\Domain\Lots\Enums;

enum LotStatus: string
{
    case PENDING = 'PENDING';
    case IN_AUCTION = 'IN_AUCTION';
    case SOLD = 'SOLD';
    case WITHDRAWN = 'WITHDRAWN';
    case ARCHIVED = 'ARCHIVED';
}
