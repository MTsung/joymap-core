<?php

namespace Mtsung\JoymapCore\Services\StoreWallet;


use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreWallet;
use Mtsung\JoymapCore\Repositories\StoreWallet\StoreWalletRepository;


/**
 * @method static StoreWallet run(Store $store)
 */
class StoreWalletGetOrCreateService
{
    use AsObject;

    public function __construct(private StoreWalletRepository $storeWalletRepository)
    {
    }

    public function handle(Store $store, int $defaultAmount = 0): StoreWallet
    {
        if ($store->storeWallet) {
            return $store->storeWallet;
        }

        Log::info('找不到儲值金錢包，建立一個', [$store->id]);

        $data = [
            'store_id' => $store->id,
            'total_amount' => $defaultAmount,
            'balance_amount' => $defaultAmount,
        ];

        return $this->storeWalletRepository->create($data);
    }
}
