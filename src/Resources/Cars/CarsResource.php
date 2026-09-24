<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars;

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Payloads\CarsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\ConstantsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\DepotsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\DepotScoresCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\DetailsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\SearchCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\SuppliersCarsRequest;
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

    /**
     * @param  list<string>  $languages
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function depots(?string $lastModified = null, ?int $maximumResults = null, array $languages = [], ?string $page = null): CarDepotsResult
    {
        $response = $this->connector->send(new DepotsCarsRequest($lastModified, $maximumResults, $languages, $page));

        /** @var CarDepotsResult $result */
        $result = $response->dto();

        return $result;
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function depotScores(?int $maximumResults = null, ?string $page = null): CarDepotScoresResult
    {
        $response = $this->connector->send(new DepotScoresCarsRequest($maximumResults, $page));

        /** @var CarDepotScoresResult $result */
        $result = $response->dto();

        return $result;
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function details(?string $lastModified = null, ?int $maximumResults = null, ?string $page = null): CarDetailsResult
    {
        $response = $this->connector->send(new DetailsCarsRequest($lastModified, $maximumResults, $page));

        /** @var CarDetailsResult $result */
        $result = $response->dto();

        return $result;
    }

    /**
     * @param  list<int>  $suppliers
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function suppliers(array $suppliers = [], ?int $maximumResults = null, ?string $page = null): CarSuppliersResult
    {
        $response = $this->connector->send(new SuppliersCarsRequest($suppliers, $maximumResults, $page));

        /** @var CarSuppliersResult $result */
        $result = $response->dto();

        return $result;
    }
}
