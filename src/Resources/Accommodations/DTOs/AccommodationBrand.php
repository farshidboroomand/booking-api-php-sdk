<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs;

final readonly class AccommodationBrand
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
