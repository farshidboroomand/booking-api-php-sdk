<?php

declare(strict_types=1);

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Enums\Extras;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationAvailabilityResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationSearchResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsAvailabilityPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\AvailabilityAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\SearchAccommodationsRequest;
use Saloon\Http\Faking\MockResponse;

describe('Accommodations Search', function (): void {
    test('it can search accommodations', function (): void {
        $connector = new Client(
            affiliateId: 1234,
            token: 'ozzy osbourne token'
        );

        $connector->withMockClient(mockClient([
            SearchAccommodationsRequest::class => fakeResponse('accommodations/search'),
        ]));

        $response = $connector->accommodations()->search(
            search: AccommodationsSearchPayload::make([
                'booker' => ['country' => 'us', 'platform' => 'desktop'],
                'checkin' => '2026-10-01',
                'checkout' => '2026-10-05',
                'guests' => ['number_of_adults' => 2, 'number_of_rooms' => 1],
                'country' => 'fr',
                'extras' => [Extras::ExtraCharges->value, Extras::Products->value],
            ]),
        );

        expect($response)->toBeInstanceOf(AccommodationSearchResult::class);
        expect($response->accommodations)->toBeArray()->each->toBeInstanceOf(Accommodation::class);
        expect($response->nextPage)->toBeString();
    });
});

test('it retrieves availability for multiple accommodations', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        AvailabilityAccommodationsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [
                ['id' => 10004, 'currency' => ['accommodation' => 'EUR', 'booker' => 'USD'], 'products' => [['id' => 'room-1', 'price' => ['display' => ['booker_currency' => 100]]]], 'recommendation' => ['products' => [['id' => 'room-1']]]],
                ['id' => 10005, 'currency' => ['accommodation' => 'EUR', 'booker' => 'USD'], 'products' => []],
            ],
        ]),
    ]));

    $payload = AccommodationsAvailabilityPayload::make([
        'accommodations' => [10004, 10005],
        'booker' => ['country' => 'nl', 'platform' => 'desktop'],
        'checkin' => '2026-10-01',
        'checkout' => '2026-10-05',
        'guests' => ['number_of_adults' => 2, 'number_of_rooms' => 1],
        'currency' => 'USD',
        'extras' => ['extra_charges'],
        'filters' => ['meal_plan' => 'breakfast_included'],
    ]);

    expect($payload->toArray())->toHaveKeys(['accommodations', 'booker', 'checkin', 'checkout', 'guests', 'currency', 'extras', 'filters']);
    $result = $client->accommodations()->availability($payload);
    expect($result)->toBeInstanceOf(AccommodationAvailabilityResult::class)
        ->and($result->accommodations)->toHaveCount(2)
        ->and($result->accommodations[0]->products[0]['id'])->toBe('room-1')
        ->and($result->accommodations[0]->recommendation['products'][0]['id'])->toBe('room-1');
});
