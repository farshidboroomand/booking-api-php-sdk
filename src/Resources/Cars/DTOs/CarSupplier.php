<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs;

final readonly class CarSupplier
{
    public function __construct(public int $id, public string $name, public ?string $logo) {}
}
