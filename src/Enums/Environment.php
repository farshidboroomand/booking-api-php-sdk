<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Enums;

enum Environment: string
{
    case Sandbox = 'https://demandapi-sandbox.booking.com/3.2';
    case Production = 'https://demandapi.booking.com/3.2';

    public function baseUrl(): string
    {
        return $this->value;
    }
}
