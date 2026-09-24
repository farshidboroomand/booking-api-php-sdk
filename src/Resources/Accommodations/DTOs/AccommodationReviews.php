<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs;

final readonly class AccommodationReviews
{
    /** @param list<array<mixed>> $reviews */
    public function __construct(public int $id, public array $reviews, public ?string $url) {}
}
