<?php

namespace Mtsung\JoymapCore\Helpers\Payment;

use Mtsung\JoymapCore\Models\Store;

interface PayInterface
{
    public function getAmountMultiplicand(): int;

    public function store(Store $store);

    public function bindCard(array $params);

    public function pay(array $params);

    public function cancel(array $params);

    public function close(array $params);

    public function query(array $params);
}
