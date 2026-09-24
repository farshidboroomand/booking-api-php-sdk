<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationReviews;

final readonly class AccommodationReviewsResult
{
    /** @param list<AccommodationReviews> $accommodations */
    public function __construct(public array $accommodations, public ?string $nextPage) {}
}
