<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs;

final readonly class CarDepotScore
{
    /** @param array<mixed> $breakdown */
    public function __construct(
        public int $id,
        public ?float $score,
        public ?int $numberOfReviews,
        public array $breakdown,
    ) {}
}
