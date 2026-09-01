<?php

declare(strict_types=1);

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Enums\Environment;

test('it resolves the sandbox base URL', function (): void {
    $client = new Client(
        affiliateId: 1234,
        token: 'votre token',
        environment: Environment::Sandbox,
    );

    expect($client->resolveBaseUrl())
        ->toEqual('https://demandapi-sandbox.booking.com/3.2');
});

test('it resolves the Production base URL', function (): void {
    $client = new Client(
        affiliateId: 1234,
        token: 'votre token',
        environment: Environment::Production,
    );

    expect($client->resolveBaseUrl())
        ->toEqual('https://demandapi.booking.com/3.2');
});

test('it defaults to the Sandbox base URL', function (): void {
    $client = new Client(
        affiliateId: 1234,
        token: 'votre token',
    );

    expect($client->resolveBaseUrl())
        ->toEqual('https://demandapi-sandbox.booking.com/3.2');
});
