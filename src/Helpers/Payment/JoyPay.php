<?php

namespace Mtsung\JoymapCore\Helpers\Payment;


use Exception;
use Mtsung\JoymapCore\Models\Store;

class JoyPay
{
    private PayInterface $service;
    private string $token = '';
    private float $amount = 0;
    private int $inst = 0;
    private ?Store $store = null;
    private ?int $storeId = null;
    private string $orderNumber = '';
    private string $expiry = '';
    private string $cvc = '';
    private string $cardNo = '';
    private string $phone = '';
    private ?string $returnUrl = null;
    private ?string $callbackUrl = null;
    private string $email = '';

    public function __construct()
    {
        if (config()->has('joymap.pay.default')) {
            $defaultChannel = config('joymap.pay.default');
            $this->service = match ($defaultChannel) {
                'spgateway' => app(SpGateway::class),
                'hitrustpay' => app(HiTrustPay::class),
                default => throw new Exception('不支援的 Pay Channel(joymap.pay.default)：' . $defaultChannel),
            };
        }
    }

    public function bySpGateway(): JoyPay
    {
        $this->service = app(SpGateway::class);
        return $this;
    }

    public function byHiTrustPay(): JoyPay
    {
        $this->service = app(HiTrustPay::class);
        return $this;
    }

    // 這是 HiTrust 的 store_id 非 db 的 stores.id
    public function storeId($storeId): JoyPay
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function store(Store $store): JoyPay
    {
        $this->store = $store;
        $this->service->store($store);
        return $this;
    }

    public function token($token): JoyPay
    {
        $this->token = $token;
        return $this;
    }

    public function money($amount): JoyPay
    {
        $this->amount = $amount * $this->service->getAmountMultiplicand();
        return $this;
    }

    public function orderNo($orderNumber): JoyPay
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function expiry($expiry): JoyPay
    {
        $this->expiry = $expiry;
        return $this;
    }

    public function cardNo($cardNo): JoyPay
    {
        $this->cardNo = $cardNo;
        return $this;
    }

    public function cvc($cvc): JoyPay
    {
        $this->cvc = $cvc;
        return $this;
    }

    public function phone($phone): JoyPay
    {
        $this->phone = $phone;
        return $this;
    }

    public function email($email): JoyPay
    {
        $this->email = $email;
        return $this;
    }

    public function inst($inst): JoyPay
    {
        $this->inst = $inst;
        return $this;
    }

    private function reset(): void
    {
        $this->token = '';
        $this->amount = 0;
        $this->inst = 0;
        $this->storeId = null;
        $this->store = null;
        $this->orderNumber = '';
        $this->expiry = '';
        $this->cvc = '';
        $this->cardNo = '';
        $this->phone = '';
        $this->returnUrl = null;
        $this->callbackUrl = null;
        $this->email = '';
    }

    /**
     * @throws Exception
     */
    public function pay()
    {
        if (!$this->store && !$this->storeId) {
            throw new Exception('請呼叫 store() 或 storeId()', 500);
        }
        if (!$this->orderNumber) {
            throw new Exception('請呼叫 orderNo()', 500);
        }
        if (!$this->amount) {
            throw new Exception('請呼叫 money()', 500);
        }
        if (!$this->token) {
            throw new Exception('請呼叫 token()', 500);
        }
        if (($this->service instanceof HiTrustPay) && !$this->expiry) {
            throw new Exception('請呼叫 expiry()', 500);
        }
        if (!$this->email) {
            throw new Exception('請呼叫 email()', 500);
        }

        $params = [
            'storeId' => $this->storeId,
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
            'orderDesc' => '享樂支付',
            'depositFlag' => 1,
            'queryFlag' => 1,
            'token' => $this->token,
            'expiry' => $this->expiry,
            'returnUrl' => $this->returnUrl,
            'callbackUrl' => $this->callbackUrl,
            'email' => $this->email,
            'inst' => $this->inst,
        ];

        $this->reset();

        return $this->service->pay($params);
    }

    /**
     * @throws Exception
     */
    public function bindCard()
    {
        if (!$this->store && !$this->storeId) {
            throw new Exception('請呼叫 store() 或 storeId()', 500);
        }
        if (!$this->cardNo) {
            throw new Exception('請呼叫 cardNo()', 500);
        }
        if (!$this->expiry) {
            throw new Exception('請呼叫 expiry()', 500);
        }
        if (!$this->cvc) {
            throw new Exception('請呼叫 cvc()', 500);
        }
        if (!$this->phone) {
            throw new Exception('請呼叫 phone()', 500);
        }
        if (!$this->email) {
            throw new Exception('請呼叫 email()', 500);
        }
        if (!$this->amount) {
            $this->money(1);
        }
        if (!$this->orderNumber) {
            $this->orderNo($this->phone . 'J' . rand(11111, 99999));
        }

        $params = [
            'storeId' => $this->storeId,
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
            'orderDesc' => '綁定信用卡刷 1 塊',
            'depositFlag' => 0,
            'queryFlag' => 1,
            'cardNo' => $this->cardNo,
            'expiry' => $this->expiry,
            'cvc' => $this->cvc,
            'e55' => 1,
            'returnUrl' => $this->returnUrl,
            'callbackUrl' => $this->callbackUrl,
            'email' => $this->email,
        ];

        $this->reset();

        return $this->service->bindCard($params);
    }

    /**
     * @throws Exception
     */
    public function cancel()
    {
        if (!$this->orderNumber) {
            throw new Exception('請呼叫 orderNo()', 500);
        }
        if (!$this->amount) {
            throw new Exception('請呼叫 money()', 500);
        }
        if (!$this->store && !$this->storeId) {
            throw new Exception('請呼叫 store() 或 storeId()', 500);
        }

        $params = [
            'storeId' => $this->storeId,
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
            'queryFlag' => 1,
            'callbackUrl' => $this->callbackUrl,
        ];

        $this->reset();

        return $this->service->cancel($params);
    }

    /**
     * @throws Exception
     */
    public function close()
    {
        if (!$this->orderNumber) {
            throw new Exception('請呼叫 orderNo()', 500);
        }
        if (!$this->amount) {
            throw new Exception('請呼叫 money()', 500);
        }
        if (!$this->store && !$this->storeId) {
            throw new Exception('請呼叫 store() 或 storeId()', 500);
        }

        $params = [
            'storeId' => $this->storeId,
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
            'queryFlag' => 1,
            'callbackUrl' => $this->callbackUrl,
        ];

        $this->reset();

        return $this->service->close($params);
    }

    /**
     * @throws Exception
     */
    public function query()
    {
        if (!$this->orderNumber) {
            throw new Exception('請呼叫 orderNo()', 500);
        }
        if (!$this->amount) {
            throw new Exception('請呼叫 money()', 500);
        }
        if (!$this->store && !$this->storeId) {
            throw new Exception('請呼叫 store() 或 storeId()', 500);
        }

        $params = [
            'storeId' => $this->storeId,
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
        ];

        $this->reset();

        return $this->service->query($params);
    }
}
