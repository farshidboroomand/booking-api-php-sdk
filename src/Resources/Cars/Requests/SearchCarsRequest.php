<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarSearchResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Payloads\CarsSearchPayload;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class SearchCarsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly CarsSearchPayload $search) {}

    public function resolveEndpoint(): string
    {
        return '/cars/search';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->search->toArray();
    }

    public function createDtoFromResponse(Response $response): CarSearchResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid car search response from API');
        }
        foreach ($data as $product) {
            if (! is_array($product)) {
                throw new JsonException('Invalid car product in search response');
            }
        }

        $nextPage = $response->json('metadata.next_page');
        $totalResults = $response->json('metadata.total_results');
        $searchToken = $response->json('search_token');

        return new CarSearchResult(
            products: $data,
            nextPage: is_string($nextPage) ? $nextPage : null,
            totalResults: is_int($totalResults) ? $totalResults : null,
            searchToken: is_string($searchToken) ? $searchToken : null,
        );
    }
}
