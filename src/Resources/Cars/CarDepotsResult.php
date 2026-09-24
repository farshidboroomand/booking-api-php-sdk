<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarDepot;

final readonly class CarDepotsResult
{
    /** @param list<CarDepot> $depots */
    public function __construct(public array $depots, public ?string $nextPage) {}
}
