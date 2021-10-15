<?php
/*
 * This file is part of the "andrey-helldar/cashier-sber-qr" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@ai-rus.com>
 *
 * @copyright 2021 Andrey Helldar
 *
 * @license MIT
 *
 * @see https://github.com/andrey-helldar/cashier-sber-qr
 */

namespace Tests\Fixtures\Factories;

use Tests\Fixtures\Models\RequestPayment;
use Tests\TestCase;

class Payment
{
    public static function create(bool $enabled_events = false): RequestPayment
    {
        return $enabled_events
            ? static::withEvents()
            : static::withoutEvents();
    }

    protected static function withoutEvents(): RequestPayment
    {
        return RequestPayment::withoutEvents(static function () {
            static::store();
        });
    }

    protected static function withEvents(): RequestPayment
    {
        return static::store();
    }

    protected static function store(): RequestPayment
    {
        return RequestPayment::create([
            'type_id'   => TestCase::MODEL_TYPE_ID,
            'status_id' => TestCase::MODEL_STATUS_ID,

            'sum'      => TestCase::PAYMENT_SUM,
            'currency' => TestCase::CURRENCY,
        ]);
    }
}
