<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

final readonly class AccommodationDetailsChangesResult
{
    /**
     * @param  list<int>  $changed
     * @param  list<int>  $opened
     * @param  array{fraud: list<int>, permanently: list<int>, temporarily: list<int>}  $closed
     */
    public function __construct(
        public array $changed,
        public array $opened,
        public array $closed,
        public string $from,
        public ?string $next,
        public int $totalChanges,
    ) {}
}
