<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarDetail;

final readonly class CarDetailsResult
{
    /** @param list<CarDetail> $cars */
    public function __construct(public array $cars, public ?string $nextPage) {}
}
