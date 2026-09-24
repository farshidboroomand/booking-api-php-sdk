<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationReviewsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationReviews;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsReviewsPayload;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class ReviewsAccommodationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly AccommodationsReviewsPayload $reviews) {}

    public function resolveEndpoint(): string
    {
        return '/accommodations/reviews';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->reviews->toArray();
    }

    public function createDtoFromResponse(Response $response): AccommodationReviewsResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid reviews response from API');
        }

        $accommodations = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || ! is_array($item['reviews'] ?? null) || ! array_is_list($item['reviews'])) {
                throw new JsonException('Invalid accommodation reviews in response from API');
            }
            foreach ($item['reviews'] as $review) {
                if (! is_array($review)) {
                    throw new JsonException('Invalid review in response from API');
                }
            }

            $accommodations[] = new AccommodationReviews(
                id: $item['id'],
                reviews: $item['reviews'],
                url: is_string($item['url'] ?? null) ? $item['url'] : null,
            );
        }

        $nextPage = $response->json('metadata.next_page');

        return new AccommodationReviewsResult($accommodations, is_string($nextPage) ? $nextPage : null);
    }
}
