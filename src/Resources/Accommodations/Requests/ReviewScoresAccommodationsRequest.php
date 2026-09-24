<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationReviewScores;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsReviewScoresPayload;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class ReviewScoresAccommodationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly AccommodationsReviewScoresPayload $scores) {}

    public function resolveEndpoint(): string
    {
        return '/accommodations/reviews/scores';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->scores->toArray();
    }

    /** @return list<AccommodationReviewScores> */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid review scores response from API');
        }

        $scores = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || (! is_int($item['score'] ?? null) && ! is_float($item['score'] ?? null)) || ! is_int($item['number_of_reviews'] ?? null)) {
                throw new JsonException('Invalid accommodation review scores in response from API');
            }

            $scores[] = new AccommodationReviewScores(
                id: $item['id'],
                score: (float) $item['score'],
                numberOfReviews: $item['number_of_reviews'],
                breakdown: is_array($item['breakdown'] ?? null) ? $item['breakdown'] : [],
                distribution: is_array($item['distribution'] ?? null) ? $item['distribution'] : [],
                url: is_string($item['url'] ?? null) ? $item['url'] : null,
            );
        }

        return $scores;
    }
}
