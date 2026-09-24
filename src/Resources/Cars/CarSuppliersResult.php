<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarSupplier;

final readonly class CarSuppliersResult
{
    /** @param list<CarSupplier> $suppliers */
    public function __construct(public array $suppliers, public ?string $nextPage, public ?int $totalResults) {}
}
