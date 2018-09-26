<?php

namespace App\Services;


use App\UserTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;

class SmsService
{
    public function sendSms(UserTransaction $userTransaction)
    {
        $approvedTransactions = $userTransaction->where([
            ['dispatch_time', '>=', Carbon::now()->subDays(1)],
            ['status_id', 1]
        ])->get();

        $phoneNumber = 79994306330;
        $api = '69CA5B6F-8AC5-75B8-AC36-E445188DC72A';
        $smsText = "Количество успешных транзакций : {$approvedTransactions->count()} ";

        $client = new Api(new ApiIdAuth($api));
        $sms = new Sms($phoneNumber, $smsText);

        $sms->test = 1;

        try {
            $sendSms = $client->smsSend($sms);
            Log::info($client->smsStatus($sendSms->ids[0])->getDescription());
        } catch (\Exception $e) {
            Log::error("Отправка сообщения не удалась {$e->getMessage()}");
        }
    }
}