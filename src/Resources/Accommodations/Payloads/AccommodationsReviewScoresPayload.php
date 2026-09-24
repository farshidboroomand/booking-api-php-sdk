<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads;

use InvalidArgumentException;

final readonly class AccommodationsReviewScoresPayload
{
    /**
     * @param  list<int>  $accommodations
     * @param  list<string>  $languages
     * @param  array<string, mixed>  $reviewer
     */
    public function __construct(
        public array $accommodations,
        public array $languages = [],
        public array $reviewer = [],
    ) {
        if (count($accommodations) < 1 || count($accommodations) > 100 || array_filter($accommodations, fn (int $id): bool => $id < 1)) {
            throw new InvalidArgumentException('Review scores requires 1 to 100 positive accommodation IDs.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $body = ['accommodations' => $this->accommodations];
        if ($this->languages !== []) {
            $body['languages'] = $this->languages;
        }
        if ($this->reviewer !== []) {
            $body['reviewer'] = $this->reviewer;
        }

        return $body;
    }
}
