<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Tests\TestCase;

pest()
    ->extend(TestCase::class)
    ->in(__DIR__);

/**
 * @param  array<string, mixed>  $mockData
 */
function mockClient(array $mockData = []): MockClient
{
    return new MockClient($mockData);
}

/**
 * @throws JsonException
 */
function fakeResponse(string $name, int $status = 200): MockResponse
{
    return MockResponse::make(
        body: json_decode(
            json: loadFixture($name),
            associative: true,
            depth: 512,
            flags: JSON_THROW_ON_ERROR,
        ),
        status: $status,
    );
}

function loadFixture(string $name): string
{
    if (! file_exists(__DIR__."/Fixtures/{$name}.json")) {
        throw new InvalidArgumentException("Fixture {$name} does not exist.");
    }

    $contents = file_get_contents(__DIR__."/Fixtures/{$name}.json");

    if (! $contents) {
        throw new RuntimeException("Could not get contents of fixture {$name}.");
    }

    return $contents;
}
