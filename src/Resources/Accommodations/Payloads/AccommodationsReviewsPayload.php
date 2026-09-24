<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads;

use InvalidArgumentException;

final readonly class AccommodationsReviewsPayload
{
    /**
     * @param  list<int>  $accommodations
     * @param  list<string>  $languages
     * @param  array<string, mixed>  $reviewer
     * @param  array{minimum?: int, maximum?: int}  $score
     */
    public function __construct(
        public array $accommodations,
        public array $languages = [],
        public ?string $lastChange = null,
        public ?string $page = null,
        public array $reviewer = [],
        public ?int $rows = null,
        public array $score = [],
    ) {
        if (count($accommodations) < 1 || count($accommodations) > 100 || array_filter($accommodations, fn (int $id): bool => $id < 1)) {
            throw new InvalidArgumentException('Reviews requires 1 to 100 positive accommodation IDs.');
        }
        if ($rows !== null && ($rows < 10 || $rows > 100 || $rows % 10 !== 0)) {
            throw new InvalidArgumentException('Reviews rows must be a multiple of 10 between 10 and 100.');
        }
        foreach ($score as $value) {
            if ($value < 1 || $value > 10) {
                throw new InvalidArgumentException('Review scores must be between 1 and 10.');
            }
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $body = ['accommodations' => $this->accommodations];
        if ($this->languages !== []) {
            $body['languages'] = $this->languages;
        }
        if ($this->lastChange !== null) {
            $body['last_change'] = $this->lastChange;
        }
        if ($this->page !== null) {
            $body['page'] = $this->page;
        }
        if ($this->reviewer !== []) {
            $body['reviewer'] = $this->reviewer;
        }
        if ($this->rows !== null) {
            $body['rows'] = $this->rows;
        }
        if ($this->score !== []) {
            $body['score'] = $this->score;
        }

        return $body;
    }
}
