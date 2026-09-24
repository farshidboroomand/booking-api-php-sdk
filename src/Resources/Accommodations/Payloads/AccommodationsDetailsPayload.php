<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads;

use InvalidArgumentException;

final readonly class AccommodationsDetailsPayload
{
    private const array FIELDS = [
        'accommodation_facilities', 'accommodation_types', 'accommodations',
        'airport', 'brands', 'city', 'country', 'extras', 'languages',
        'page', 'payment', 'region', 'rows',
    ];

    /** @param array<string, mixed> $criteria */
    public function __construct(public array $criteria)
    {
        foreach ($criteria as $key => $value) {
            if (! in_array($key, self::FIELDS, true)) {
                throw new InvalidArgumentException("Unknown accommodation details field: {$key}");
            }
        }

        $ids = $criteria['accommodations'] ?? null;
        if ($ids !== null && (! is_array($ids) || ! array_is_list($ids) || count($ids) > 100 || array_filter($ids, fn (mixed $id): bool => ! is_int($id) || $id < 1))) {
            throw new InvalidArgumentException('Details accepts at most 100 positive accommodation IDs.');
        }

        if (! isset($criteria['page']) && ($ids === null || $ids === []) && ! isset($criteria['airport']) && ! isset($criteria['city']) && ! isset($criteria['country']) && ! isset($criteria['region'])) {
            throw new InvalidArgumentException('Details requires accommodations or a location.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->criteria;
    }
}
