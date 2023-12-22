<?php

namespace Mtsung\JoymapCore\Services\StoreWallet;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\AdminUser;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreUser;
use Mtsung\JoymapCore\Models\StoreWallet;
use Mtsung\JoymapCore\Models\StoreWalletTransactionRecord;


/**
 * @method static self make()
 */
class StoreWalletTransactionService
{
    use AsObject;

    private ?PayLog $payLog = null;

    private ?Member $member = null;

    private ?StoreUser $storeUser = null;

    private ?AdminUser $adminUser = null;

    public function __construct()
    {
    }

    public function payLog(PayLog $payLog): self
    {
        $this->payLog = $payLog;

        return $this;
    }

    public function member(Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function storeUser(StoreUser $storeUser): self
    {
        $this->storeUser = $storeUser;

        return $this;
    }

    public function adminUser(AdminUser $adminUser): self
    {
        $this->adminUser = $adminUser;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function handle(
        Store  $store,
        int    $type,
        int    $amount,
        string $comment = null,
        bool   $isDisplayComment = false
    ): bool
    {
        if ($amount === 0) {
            Log::info('儲值金異動為 0 skip.');
            return true;
        }

        return DB::transaction(function () use ($store, $type, $amount, $comment, $isDisplayComment) {
            StoreWalletGetOrCreateService::run($store);

            /** @var StoreWallet $storeWallet */
            $storeWallet = $store->storeWallet()->lockForUpdate()->first();

            $beforeAmount = $storeWallet->balance_amount;
            $afterAmount = $beforeAmount + $amount;
            $walletTotalAmount = $storeWallet->total_amount;
            if ($amount > 0) {
                $walletTotalAmount += $amount;
            }

            $storeWallet->storeWalletTransactionRecords()->create([
                'store_id' => $store->id,
                'member_id' => $this->member?->id ?? $this->payLog?->member_id,
                'pay_log_id' => $this->payLog?->id,
                'type' => $type,
                'amount' => $amount,
                'before_amount' => $beforeAmount,
                'after_amount' => $afterAmount,
                'admin_user_id' => $this->adminUser?->id,
                'status' => StoreWalletTransactionRecord::STATUS_SUCCESS,
                'is_manual_adjusted' => isset($this->adminUser),
                'comment' => $comment,
                'is_display_comment' => $isDisplayComment,
            ]);

            return $storeWallet->update([
                'total_amount' => $walletTotalAmount,
                'balance_amount' => $afterAmount,
            ]);
        });
    }
}
