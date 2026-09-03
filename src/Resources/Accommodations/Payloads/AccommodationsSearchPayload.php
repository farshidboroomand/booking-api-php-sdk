<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads;

final class AccommodationsSearchPayload
{
    /**
     * @param  array<string, mixed>  $booker
     * @param  array<string, mixed>  $guests
     * @param  array<string, mixed>  $accommodations
     * @param  array<string, mixed>  $filters
     * @param  array<string, mixed>  $sort
     */
    public function __construct(
        public array $booker,
        public string $checkin,
        public string $checkout,
        public array $guests,
        public array $accommodations = [],
        public ?string $airport = null,
        public ?int $city = null,
        public ?string $country = null,
        public ?string $currency = null,
        public array $filters = [],
        public ?string $page = null,
        public int $rows = 25,
        public array $sort = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data): self
    {
        /** @var array<string, mixed> $booker */
        $booker = isset($data['booker']) && is_array($data['booker']) ? $data['booker'] : [];

        /** @var array<string, mixed> $guests */
        $guests = isset($data['guests']) && is_array($data['guests']) ? $data['guests'] : [];

        /** @var array<string, mixed> $accommodations */
        $accommodations = isset($data['accommodations']) && is_array($data['accommodations']) ? $data['accommodations'] : [];

        /** @var array<string, mixed> $filters */
        $filters = isset($data['filters']) && is_array($data['filters']) ? $data['filters'] : [];

        /** @var array<string, mixed> $sort */
        $sort = isset($data['sort']) && is_array($data['sort']) ? $data['sort'] : [];

        return new self(
            booker: $booker,
            checkin: isset($data['checkin']) && is_string($data['checkin']) ? $data['checkin'] : '',
            checkout: isset($data['checkout']) && is_string($data['checkout']) ? $data['checkout'] : '',
            guests: $guests,
            accommodations: $accommodations,
            airport: isset($data['airport']) && is_string($data['airport']) ? $data['airport'] : null,
            city: isset($data['city']) && is_int($data['city']) ? $data['city'] : null,
            country: isset($data['country']) && is_string($data['country']) ? $data['country'] : null,
            currency: isset($data['currency']) && is_string($data['currency']) ? $data['currency'] : null,
            filters: $filters,
            page: isset($data['page']) && is_string($data['page']) ? $data['page'] : null,
            rows: isset($data['rows']) && is_int($data['rows']) ? $data['rows'] : 25,
            sort: $sort,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if (is_string($this->page)) {
            return ['page' => $this->page];
        }

        $payload = [
            'booker' => $this->booker,
            'checkin' => $this->checkin,
            'checkout' => $this->checkout,
            'guests' => $this->guests,
        ];

        if (count($this->accommodations) > 0) {
            $payload['accommodations'] = $this->accommodations;
        }

        if (is_string($this->airport)) {
            $payload['airport'] = $this->airport;
        }

        if (is_int($this->city)) {
            $payload['city'] = $this->city;
        }

        if (is_string($this->country)) {
            $payload['country'] = $this->country;
        }

        if (is_string($this->currency)) {
            $payload['currency'] = $this->currency;
        }

        if (count($this->filters) > 0) {
            $payload['filters'] = $this->filters;
        }

        if ($this->rows !== 25) {
            $payload['rows'] = $this->rows;
        }

        if (count($this->sort) > 0) {
            $payload['sort'] = $this->sort;
        }

        return $payload;
    }
}
