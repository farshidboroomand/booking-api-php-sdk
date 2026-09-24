<?php

declare(strict_types=1);

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Enums\Extras;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationAvailabilityResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationConstantsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationDetailsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationSearchResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationBrand;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationChain;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsAvailabilityPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsDetailsPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\AvailabilityAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\ChainsAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\ConstantsAccommodationsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\DetailsAccommodationsRequest;
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

test('it retrieves chains and their brands', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        ChainsAccommodationsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [
                ['id' => 1, 'name' => 'Example Group', 'brands' => [
                    ['id' => 10, 'name' => 'Example Hotels'],
                ]],
                ['id' => 2, 'name' => 'Another Group', 'brands' => []],
            ],
        ]),
    ]));

    $chains = $client->accommodations()->chains();

    expect($chains)->toHaveCount(2)
        ->and($chains[0])->toBeInstanceOf(AccommodationChain::class)
        ->and($chains[0]->name)->toBe('Example Group')
        ->and($chains[0]->brands[0])->toBeInstanceOf(AccommodationBrand::class)
        ->and($chains[0]->brands[0]->id)->toBe(10)
        ->and($chains[1]->brands)->toBe([]);
});

test('it retrieves selected localized accommodation constants', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        ConstantsAccommodationsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [
                'room_types' => [['id' => 1, 'name' => ['en-gb' => 'Single room']]],
                'review_scores' => [['minimum_score' => 9, 'maximum_score' => 10, 'name' => ['en-gb' => 'Superb']]],
            ],
        ]),
    ]));

    $result = $client->accommodations()->constants(['room_types', 'review_scores'], ['en-gb']);

    expect($result)->toBeInstanceOf(AccommodationConstantsResult::class)
        ->and($result->sections['room_types'][0]['name']['en-gb'])->toBe('Single room')
        ->and($result->sections['review_scores'][0]['minimum_score'])->toBe(9);
});

test('it retrieves accommodation details and pagination', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        DetailsAccommodationsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [['id' => 10004, 'name' => ['en-gb' => 'Example Hotel'], 'rooms' => [['id' => 'room-a']]]],
            'metadata' => ['next_page' => 'cursor-2'],
        ]),
    ]));

    $result = $client->accommodations()->details(new AccommodationsDetailsPayload([
        'accommodations' => [10004],
        'extras' => ['rooms'],
        'languages' => ['en-gb'],
    ]));

    expect($result)->toBeInstanceOf(AccommodationDetailsResult::class)
        ->and($result->accommodations[0]->id)->toBe(10004)
        ->and($result->accommodations[0]->data['rooms'][0]['id'])->toBe('room-a')
        ->and($result->nextPage)->toBe('cursor-2');
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
