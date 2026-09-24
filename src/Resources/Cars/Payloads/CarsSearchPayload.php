<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Payloads;

use InvalidArgumentException;

final readonly class CarsSearchPayload
{
    /**
     * @param  array<string, mixed>  $booker
     * @param  array<string, mixed>  $driver
     * @param  array<string, mixed>  $route
     * @param  array<string, mixed>  $filters
     * @param  array<string, mixed>  $payment
     * @param  array<string, mixed>  $sort
     */
    public function __construct(
        public array $booker,
        public string $currency,
        public array $driver,
        public array $route,
        public array $filters = [],
        public ?int $maximumResults = null,
        public ?string $language = null,
        public ?string $page = null,
        public array $payment = [],
        public array $sort = [],
    ) {
        if ($maximumResults !== null && ($maximumResults < 10 || $maximumResults > 500 || $maximumResults % 10 !== 0)) {
            throw new InvalidArgumentException('Car search maximum_results must be a multiple of 10 between 10 and 500.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $body = [
            'booker' => $this->booker,
            'currency' => $this->currency,
            'driver' => $this->driver,
            'route' => $this->route,
        ];
        if ($this->filters !== []) {
            $body['filters'] = $this->filters;
        }
        if ($this->maximumResults !== null) {
            $body['maximum_results'] = $this->maximumResults;
        }
        if ($this->language !== null) {
            $body['language'] = $this->language;
        }
        if ($this->page !== null) {
            $body['page'] = $this->page;
        }
        if ($this->payment !== []) {
            $body['payment'] = $this->payment;
        }
        if ($this->sort !== []) {
            $body['sort'] = $this->sort;
        }

        return $body;
    }
}
