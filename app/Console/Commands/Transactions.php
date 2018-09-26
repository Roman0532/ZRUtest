<?php

namespace App\Console\Commands;

use App\Services\TransactionsService;
use App\UserTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Transactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:begin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Комманда для запуска отложеных платежей';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Запуск транзакции
     *
     * @param UserTransaction $userTransaction
     * @return mixed
     */
    public function handle(UserTransaction $userTransaction)
    {
        Log::info('Старт транзакции ' . Carbon::now());
        try {
            $transactionsService = new TransactionsService($userTransaction);

            $transactionsService->startingTransactions();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . Carbon::now());
        }
    }
}
