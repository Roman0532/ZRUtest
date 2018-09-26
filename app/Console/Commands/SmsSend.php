<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use App\UserTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SmsSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда для отправки смс';

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
     * Запуск рассылки смс
     *
     * @param UserTransaction $userTransaction
     * @return mixed
     */
    public function handle(UserTransaction $userTransaction)
    {
        Log::info('Начало отправки смс ' . Carbon::now());

        $smsService = new SmsService();
        $smsService->sendSms($userTransaction);
    }
}
