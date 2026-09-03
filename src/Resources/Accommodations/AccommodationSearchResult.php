<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;

final readonly class AccommodationSearchResult
{
    /**
     * @param  array<int, Accommodation>  $accommodations
     */
    public function __construct(
        public array $accommodations,
        public ?string $nextPage,
    ) {}
}
