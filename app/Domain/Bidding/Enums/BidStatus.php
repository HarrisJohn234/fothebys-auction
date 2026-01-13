<?php

namespace App\Domain\Bidding\Enums;

enum BidStatus: string
{
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
    case CANCELLED = 'CANCELLED';
    case EXECUTED = 'EXECUTED';
}
