<?php

namespace Helldar\CashierDriver\Sber\QrCode;

use Helldar\Cashier\DTO\Request;
use Helldar\Cashier\Resources\Response;
use Helldar\Cashier\Services\Driver as BaseDriver;
use Helldar\CashierDriver\Sber\Auth\DTO\Client;
use Helldar\CashierDriver\Sber\Auth\Facades\Auth;
use Helldar\CashierDriver\Sber\QrCode\Helpers\Exception;
use Helldar\CashierDriver\Sber\QrCode\Helpers\Statuses;
use Psr\Http\Message\UriInterface;

class Driver extends BaseDriver
{
    protected $statuses = Statuses::class;

    protected $response = Resources\Response::class;

    protected $exception = Exception::class;

    protected $production_host = 'https://api.sberbank.ru';

    protected $dev_host = 'https://dev.api.sberbank.ru';

    protected $uri_create = '/ru/prod/order/v1/creation';

    protected $uri_status = '/ru/prod/order/v1/status';

    protected $uri_revocation = '/ru/prod/order/v1/revocation';

    protected $scope_create = 'https://api.sberbank.ru/order.create';

    protected $scope_status = 'https://api.sberbank.ru/order.status';

    protected $scope_cancel = 'https://api.sberbank.ru/order.revoke';

    public function start(): Response
    {
        $request = $this->requestDto(
            $this->url($this->uri_create),
            $this->resource->toArray(),
            $this->headers($this->scope_create)
        );

        return $this->request($request);
    }

    public function check(): Response
    {
        $request = $this->requestDto(
            $this->url($this->uri_status),
            [
                'rq_uid' => $this->resource->getUniqueId(),

                'rq_tm' => $this->resource->getNow(),

                'order_id' => $this->model->cashier->payment_id,
            ],
            $this->headers($this->scope_status)
        );

        return $this->request($request, false);
    }

    public function refund(): Response
    {
        $request = $this->requestDto(
            $this->url($this->uri_revocation),
            [
                'rq_uid' => $this->resource->getUniqueId(),

                'rq_tm' => $this->resource->getNow(),

                'order_id' => $this->model->cashier->payment_id,
            ],
            $this->headers($this->scope_cancel)
        );

        return $this->request($request);
    }

    protected function headers(string $scope): array
    {
        return [
            'Authorization' => 'Bearer '.$this->accessToken($scope),

            'X-IBM-Client-Id' => $this->auth->getClientId(),

            'x-Introspect-RqUID' => $this->resource->getUniqueId(),
        ];
    }

    protected function accessToken(string $scope): string
    {
        $auth = $this->authDto($scope);

        return Auth::accessToken($auth);
    }

    protected function authDto(string $scope): Client
    {
        return Client::make()
            ->scope($scope)
            ->host($this->host())
            ->clientId($this->auth->getClientId())
            ->clientSecret($this->auth->getClientSecret())
            ->memberId($this->resource->getMemberId())
            ->paymentId($this->resource->getPaymentId());
    }

    protected function requestDto(UriInterface $url, array $data, array $headers): Request
    {
        return Request::make()
            ->setUrl($url)
            ->setData($data)
            ->setHeaders($headers);
    }
}
