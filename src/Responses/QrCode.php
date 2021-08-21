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

declare(strict_types=1);

namespace Helldar\CashierDriver\Sber\QrCode\Responses;

use Helldar\Cashier\Http\Response;

class QrCode extends Response
{
    public const KEY_URL = 'url';

    protected $map = [
        self::KEY_EXTERNAL_ID => 'status.order_id',

        self::KEY_STATUS => 'status.order_state',

        self::KEY_URL => 'status.order_form_url',
    ];

    public function getUrl(): ?string
    {
        return $this->value(self::KEY_URL);
    }
}