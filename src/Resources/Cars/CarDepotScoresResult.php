<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarDepotScore;

final readonly class CarDepotScoresResult
{
    /** @param list<CarDepotScore> $scores */
    public function __construct(public array $scores, public ?string $nextPage) {}
}
