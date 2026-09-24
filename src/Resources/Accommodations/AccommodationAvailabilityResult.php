<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationAvailability;

final readonly class AccommodationAvailabilityResult
{
    /** @param list<AccommodationAvailability> $accommodations */
    public function __construct(public array $accommodations) {}
}
