<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations;

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationAvailability;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationChain;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsAvailabilityPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsDetailsPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\AvailabilityAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\ChainsAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\ConstantsAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\DetailsAccommodationsRequest;
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

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function availability(AccommodationsAvailabilityPayload $availability): AccommodationAvailabilityResult
    {
        $response = $this->connector->send(new AvailabilityAccommodationsRequest($availability));

        /** @var list<AccommodationAvailability> $accommodations */
        $accommodations = $response->dto();

        return new AccommodationAvailabilityResult(accommodations: $accommodations);
    }

    /**
     * @return list<AccommodationChain>
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function chains(): array
    {
        $response = $this->connector->send(new ChainsAccommodationsRequest);

        /** @var list<AccommodationChain> $chains */
        $chains = $response->dto();

        return $chains;
    }

    /**
     * @param  list<string>  $constants
     * @param  list<string>  $languages
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function constants(array $constants = [], array $languages = []): AccommodationConstantsResult
    {
        $response = $this->connector->send(new ConstantsAccommodationsRequest($constants, $languages));

        /** @var AccommodationConstantsResult $result */
        $result = $response->dto();

        return $result;
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function details(AccommodationsDetailsPayload $details): AccommodationDetailsResult
    {
        $response = $this->connector->send(new DetailsAccommodationsRequest($details));

        /** @var AccommodationDetailsResult $result */
        $result = $response->dto();

        return $result;
    }
}
