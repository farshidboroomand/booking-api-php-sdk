<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads;

use InvalidArgumentException;

final readonly class AccommodationsAvailabilityPayload
{
    /**
     * @param  list<int>  $accommodations
     * @param  array<string, mixed>  $booker
     * @param  array<string, mixed>  $guests
     * @param  list<string>  $extras
     * @param  array<string, mixed>  $filters
     */
    public function __construct(
        public array $accommodations,
        public array $booker,
        public string $checkin,
        public string $checkout,
        public array $guests,
        public ?string $currency = null,
        public array $extras = [],
        public array $filters = [],
    ) {
        if (count($accommodations) < 1 || count($accommodations) > 50 || array_filter($accommodations, fn (int $id): bool => $id < 1)) {
            throw new InvalidArgumentException('Availability requires 1 to 50 positive accommodation IDs.');
        }
    }

    /** @param array{accommodations: list<int>, booker: array<string, mixed>, checkin: string, checkout: string, guests: array<string, mixed>, currency?: string, extras?: list<string>, filters?: array<string, mixed>} $data */
    public static function make(array $data): self
    {
        return new self(
            accommodations: $data['accommodations'],
            booker: $data['booker'],
            checkin: $data['checkin'],
            checkout: $data['checkout'],
            guests: $data['guests'],
            currency: $data['currency'] ?? null,
            extras: $data['extras'] ?? [],
            filters: $data['filters'] ?? [],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $body = [
            'accommodations' => $this->accommodations,
            'booker' => $this->booker,
            'checkin' => $this->checkin,
            'checkout' => $this->checkout,
            'guests' => $this->guests,
        ];

        if ($this->currency !== null) {
            $body['currency'] = $this->currency;
        }
        if ($this->extras !== []) {
            $body['extras'] = $this->extras;
        }
        if ($this->filters !== []) {
            $body['filters'] = $this->filters;
        }

        return $body;
    }
}
