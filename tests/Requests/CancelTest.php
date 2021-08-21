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

namespace Tests\Requests;

use Helldar\Cashier\Http\Request;
use Helldar\CashierDriver\Sber\QrCode\Constants\Body;
use Helldar\CashierDriver\Sber\QrCode\Requests\Cancel;
use Helldar\Contracts\Cashier\Http\Request as RequestContract;
use Helldar\Contracts\Http\Builder;
use Helldar\Support\Facades\Helpers\Arr;
use Tests\TestCase;

class CancelTest extends TestCase
{
    public function testInstance()
    {
        $request = $this->request(Cancel::class);

        $this->assertInstanceOf(Cancel::class, $request);
        $this->assertInstanceOf(Request::class, $request);
        $this->assertInstanceOf(RequestContract::class, $request);
    }

    public function testUri()
    {
        $request = $this->request(Cancel::class);

        $this->assertInstanceOf(Builder::class, $request->uri());

        $this->assertSame('https://dev.api.sberbank.ru/ru/prod/order/v1/revocation', $request->uri()->toUrl());
    }

    public function testHeaders()
    {
        $request = $this->request(Cancel::class);

        $headers = $request->headers();

        $this->assertIsArray($headers);

        $this->assertArrayHasKey('Accept', $headers);
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertArrayHasKey('X-IBM-Client-Id', $headers);
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('x-Introspect-RqUID', $headers);

        $this->assertSame('application/json', Arr::get($headers, 'Accept'));
        $this->assertSame('application/json', Arr::get($headers, 'Content-Type'));

        $this->assertSame($this->getClientId(), Arr::get($headers, 'X-IBM-Client-Id'));
    }

    public function testGetRawHeaders()
    {
        $request = $this->request(Cancel::class);

        $this->assertIsArray($request->getRawHeaders());

        $this->assertSame([
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ], $request->getRawHeaders());
    }

    public function testBody()
    {
        $request = $this->request(Cancel::class);

        $body = $request->body();

        $this->assertIsArray($body);

        $this->assertArrayHasKey(Body::REQUEST_ID, $body);
        $this->assertArrayHasKey(Body::REQUEST_TIME, $body);
        $this->assertArrayHasKey(Body::EXTERNAL_ID, $body);

        $this->assertSame(self::PAYMENT_EXTERNAL_ID, Arr::get($body, Body::EXTERNAL_ID));
    }

    public function testGetRawBody()
    {
        $request = $this->request(Cancel::class);

        $body = $request->getRawBody();

        $this->assertIsArray($body);

        $this->assertArrayHasKey(Body::REQUEST_ID, $body);
        $this->assertArrayHasKey(Body::REQUEST_TIME, $body);
        $this->assertArrayHasKey(Body::EXTERNAL_ID, $body);

        $this->assertSame(self::PAYMENT_EXTERNAL_ID, Arr::get($body, Body::EXTERNAL_ID));
    }
}
