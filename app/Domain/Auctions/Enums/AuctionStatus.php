<?php

namespace App\Domain\Auctions\Enums;

enum AuctionStatus: string
{
    case DRAFT = 'DRAFT';
    case SCHEDULED = 'SCHEDULED';
    case LIVE = 'LIVE';
    case CLOSED = 'CLOSED';
    case ARCHIVED = 'ARCHIVED';
}
