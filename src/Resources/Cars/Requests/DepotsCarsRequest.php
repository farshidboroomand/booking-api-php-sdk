<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarDepotsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarDepot;
use InvalidArgumentException;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasStringBody;

final class DepotsCarsRequest extends Request implements HasBody
{
    use HasStringBody;

    protected Method $method = Method::POST;

    /** @param list<string> $languages */
    public function __construct(
        private readonly ?string $lastModified = null,
        private readonly ?int $maximumResults = null,
        private readonly array $languages = [],
        private readonly ?string $page = null,
    ) {
        if ($maximumResults !== null && ($maximumResults < 10 || $maximumResults > 100 || $maximumResults % 10 !== 0)) {
            throw new InvalidArgumentException('Depots maximum_results must be a multiple of 10 between 10 and 100.');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/cars/depots';
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        return ['Content-Type' => 'application/json'];
    }

    protected function defaultBody(): string
    {
        $body = [];
        if ($this->lastModified !== null) {
            $body['last_modified'] = $this->lastModified;
        }
        if ($this->maximumResults !== null) {
            $body['maximum_results'] = $this->maximumResults;
        }
        if ($this->languages !== []) {
            $body['languages'] = $this->languages;
        }
        if ($this->page !== null) {
            $body['page'] = $this->page;
        }

        return $body === [] ? '{}' : json_encode($body, JSON_THROW_ON_ERROR);
    }

    public function createDtoFromResponse(Response $response): CarDepotsResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid car depots response from API');
        }

        $depots = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null)) {
                throw new JsonException('Invalid car depot in response');
            }
            $depots[] = new CarDepot($item['id'], $item);
        }

        $nextPage = $response->json('metadata.next_page');

        return new CarDepotsResult($depots, is_string($nextPage) ? $nextPage : null);
    }
}
