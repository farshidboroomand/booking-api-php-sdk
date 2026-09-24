<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Payloads\CarsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\ConstantsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\SearchCarsRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final readonly class CarsResource
{
    public function __construct(private Client $connector) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function search(CarsSearchPayload $search): CarSearchResult
    {
        $response = $this->connector->send(new SearchCarsRequest($search));

        /** @var CarSearchResult $result */
        $result = $response->dto();

        return $result;
    }

    /**
     * @param  list<string>  $constants
     * @param  list<string>  $languages
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function constants(array $constants = [], array $languages = []): CarConstantsResult
    {
        $response = $this->connector->send(new ConstantsCarsRequest($constants, $languages));

        /** @var CarConstantsResult $result */
        $result = $response->dto();

        return $result;
    }
}
