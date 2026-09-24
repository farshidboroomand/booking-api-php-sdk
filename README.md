# Booking.com Demand API PHP SDK

An unofficial PHP SDK for the [Booking.com Demand API v3.2](https://developers.booking.com/demand/docs/open-api/3.2/demand-api). It supports accommodation and car rental endpoints. The API may change before a stable release.

## Requirements

- PHP 8.3 or newer
- Composer 2 or newer
- Booking.com Affiliate ID and API token

## Installation

```bash
composer require farshidboroomand/booking-api-php-sdk
```

## Usage

Create a client with your credentials. The sandbox is the default environment; pass `Environment::Production` for live requests.

```php
use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;

$client = new Client(affiliateId: 1234, token: 'your-api-token');

$result = $client->accommodations()->search(
    search: AccommodationsSearchPayload::make([
        'booker' => ['country' => 'nl', 'platform' => 'desktop'],
        'checkin' => '2026-09-01',
        'checkout' => '2026-09-10',
        'guests' => ['number_of_adults' => 2, 'number_of_rooms' => 1],
        'city' => -2140479,
    ]),
);

$accommodations = $result->accommodations;
$nextPage = $result->nextPage;
```

## Implemented APIs

All endpoints use the Demand API v3.2. Call accommodation methods through `$client->accommodations()` and car methods through `$client->cars()`.

| Endpoint | SDK method |
| --- | --- |
| `POST /accommodations/search` | `search($payload)` |
| `POST /accommodations/availability` | `availability($payload)` |
| `POST /accommodations/chains` | `chains()` |
| `POST /accommodations/constants` | `constants($sections, $languages)` |
| `POST /accommodations/details` | `details($payload)` |
| `POST /accommodations/details/changes` | `detailsChanges($lastChange, $countries, $cities)` |
| `POST /accommodations/reviews` | `reviews($payload)` |
| `POST /accommodations/reviews/scores` | `reviewScores($payload)` |
| `POST /accommodations/third-party-suppliers` | `thirdPartySuppliers()` |
| `POST /cars/search` | `search($payload)` |
| `POST /cars/constants` | `constants($sections, $languages)` |
| `POST /cars/depots` | `depots($lastModified, $maximumResults, $languages, $page)` |
| `POST /cars/depots/reviews/scores` | `depotScores($maximumResults, $page)` |
| `POST /cars/details` | `details($lastModified, $maximumResults, $page)` |
| `POST /cars/suppliers` | `suppliers($suppliers, $maximumResults, $page)` |

Payload classes are in `Resources\Accommodations\Payloads` and `Resources\Cars\Payloads`. Paginated results expose `nextPage`.

## Development

```bash
composer install
composer test
composer stan
composer lint
```

Tests use local mock responses and do not call Booking.com.

## License

MIT. This project is not affiliated with or endorsed by Booking.com.
