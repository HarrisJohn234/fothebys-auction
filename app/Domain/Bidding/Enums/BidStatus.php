<?php

namespace App\Domain\Bidding\Enums;

enum BidStatus: string
{
    case ACTIVE = 'active';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case EXECUTED = 'executed';
}
