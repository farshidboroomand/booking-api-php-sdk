<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs;

final readonly class Accommodation
{
    /**
     * @param  array<string, mixed>  $currency
     * @param  array<string, mixed>|null  $commission
     * @param  array<string, mixed>|null  $price
     * @param  array<string, mixed>|null  $products
     */
    public function __construct(
        public int $id,
        public array $currency,
        public ?string $deep_link_url = null,
        public ?string $url = null,
        public ?array $commission = null,
        public ?array $price = null,
        public ?array $products = null,
    ) {}
}
