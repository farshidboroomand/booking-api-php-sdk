<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationDetail;

final readonly class AccommodationDetailsResult
{
    /** @param list<AccommodationDetail> $accommodations */
    public function __construct(public array $accommodations, public ?string $nextPage) {}
}
