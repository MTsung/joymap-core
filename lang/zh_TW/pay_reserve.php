<?php

use Mtsung\JoymapCore\Models\PayReserve;

return [
    'status' => [
        PayReserve::STATUS_FAILED => '支付失敗',
        PayReserve::STATUS_PENDING => '等待支付中',
        PayReserve::STATUS_SUCCESS => '支付成功',
        PayReserve::STATUS_EXPIRED => '支付過期',
        PayReserve::STATUS_PARTIAL_REFUND => '部分退款',
        PayReserve::STATUS_FULL_REFUND => '全額退款',
    ],
];
