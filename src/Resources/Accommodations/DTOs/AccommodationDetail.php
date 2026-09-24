<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs;

final readonly class AccommodationDetail
{
    /** @param array<mixed> $data Complete API details record. */
    public function __construct(public int $id, public array $data) {}
}
