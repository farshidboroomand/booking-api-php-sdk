<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs;

final readonly class AccommodationAvailability
{
    /**
     * @param  array<mixed>  $currency
     * @param  array<mixed>  $products
     * @param  array<mixed>|null  $recommendation
     */
    public function __construct(
        public int $id,
        public array $currency,
        public array $products,
        public ?array $recommendation = null,
        public ?string $deep_link_url = null,
        public ?string $url = null,
    ) {}
}
