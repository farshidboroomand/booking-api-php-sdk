<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs;

final readonly class CarDepot
{
    /** @param array<mixed> $data Complete depot record. */
    public function __construct(public int $id, public array $data) {}
}
