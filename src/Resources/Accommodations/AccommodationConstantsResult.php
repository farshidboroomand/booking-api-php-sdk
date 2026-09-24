<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

final readonly class AccommodationConstantsResult
{
    /**
     * Each requested section is a list of reference records with localized names.
     *
     * @param  array<string, list<array<mixed>>>  $sections
     */
    public function __construct(public array $sections) {}
}
