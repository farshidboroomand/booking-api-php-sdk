<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

final readonly class CarSearchResult
{
    /** @param list<array<mixed>> $products */
    public function __construct(
        public array $products,
        public ?string $nextPage,
        public ?int $totalResults,
        public ?string $searchToken,
    ) {}
}
