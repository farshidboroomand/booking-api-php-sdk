<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs;

final readonly class CarDetail
{
    /** @param array<mixed> $data Complete car specification record. */
    public function __construct(public int $id, public array $data) {}
}
