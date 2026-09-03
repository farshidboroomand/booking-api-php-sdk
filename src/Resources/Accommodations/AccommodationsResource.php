<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\SearchAccommodationsRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final readonly class AccommodationsResource
{
    public function __construct(
        private Client $connector,
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function search(AccommodationsSearchPayload $search): AccommodationSearchResult
    {
        $response = $this->connector->send(
            request: new SearchAccommodationsRequest(search: $search),
        );

        /** @var array<int, Accommodation> $accommodations */
        $accommodations = $response->dto();

        /** @var string|null $nextPage */
        $nextPage = is_string($response->json('metadata.next_page')) ? $response->json('metadata.next_page') : null;

        return new AccommodationSearchResult(
            accommodations: $accommodations,
            nextPage: $nextPage,
        );
    }
}
