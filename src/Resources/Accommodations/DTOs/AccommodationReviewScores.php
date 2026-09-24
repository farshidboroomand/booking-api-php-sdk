<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs;

final readonly class AccommodationReviewScores
{
    /**
     * @param  array<mixed>  $breakdown
     * @param  array<mixed>  $distribution
     */
    public function __construct(
        public int $id,
        public float $score,
        public int $numberOfReviews,
        public array $breakdown,
        public array $distribution,
        public ?string $url,
    ) {}
}
