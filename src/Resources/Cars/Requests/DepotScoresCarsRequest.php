<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarDepotScoresResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarDepotScore;
use InvalidArgumentException;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasStringBody;

final class DepotScoresCarsRequest extends Request implements HasBody
{
    use HasStringBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly ?int $maximumResults = null, private readonly ?string $page = null)
    {
        if ($maximumResults !== null && ($maximumResults < 10 || $maximumResults > 100 || $maximumResults % 10 !== 0)) {
            throw new InvalidArgumentException('Depot scores maximum_results must be a multiple of 10 between 10 and 100.');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/cars/depots/reviews/scores';
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        return ['Content-Type' => 'application/json'];
    }

    protected function defaultBody(): string
    {
        $body = [];
        if ($this->maximumResults !== null) {
            $body['maximum_results'] = $this->maximumResults;
        }
        if ($this->page !== null) {
            $body['page'] = $this->page;
        }

        return $body === [] ? '{}' : json_encode($body, JSON_THROW_ON_ERROR);
    }

    public function createDtoFromResponse(Response $response): CarDepotScoresResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid depot scores response from API');
        }

        $scores = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || (isset($item['score']) && ! is_int($item['score']) && ! is_float($item['score'])) || (isset($item['number_of_reviews']) && ! is_int($item['number_of_reviews']))) {
                throw new JsonException('Invalid depot score in response');
            }
            $score = $item['score'] ?? null;
            $scores[] = new CarDepotScore(
                id: $item['id'],
                score: is_int($score) || is_float($score) ? (float) $score : null,
                numberOfReviews: $item['number_of_reviews'] ?? null,
                breakdown: is_array($item['breakdown'] ?? null) ? $item['breakdown'] : [],
            );
        }

        $nextPage = $response->json('metadata.next_page');

        return new CarDepotScoresResult($scores, is_string($nextPage) ? $nextPage : null);
    }
}
