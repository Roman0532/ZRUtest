<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 23.09.18
 * Time: 20:20
 */

namespace App\Services;

use App\UserTransaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;
use Zelenin\SmsRu\Exception\Exception;

class TransactionsService
{
    private $userTransaction;

    /**
     * TransactionsService constructor.
     * @param UserTransaction $userTransaction
     */
    public function __construct(UserTransaction $userTransaction)
    {
        $this->userTransaction = $userTransaction;
    }

    public function startingTransactions()
    {
        $waitingTransactions = $this->userTransaction->where([
            ['status_id', 3],
            ['dispatch_time', '<=', Carbon::now()]
        ])->get();

        foreach ($waitingTransactions as $transaction) {
            DB::beginTransaction();
            try {
                $newUserBalance = $transaction->fromUser->balance - $transaction->amount;
                $newUserToBalance = $transaction->toUser->balance + $transaction->amount;

                $transaction->fromUser->update(['balance' => $newUserBalance]);
                $transaction->toUser->update(['balance' => $newUserToBalance]);
                $transaction->update(['status_id' => 1]);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error("Транзакция не удалась {$exception->getMessage()}");
                $transaction->update(['status_id' => 2]);
            }
        }
    }
}
