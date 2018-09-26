<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 26.09.18
 * Time: 15:40
 */

namespace App\Services;


use App\UserTransaction;

class CsvTransactionsService
{
    private $userTransaction;

    /**
     * CsvTransactionsService constructor.
     * @param UserTransaction $userTransaction
     */
    public function __construct(UserTransaction $userTransaction)
    {
        $this->userTransaction = $userTransaction;
    }

    public function writeApprovedTransactionsInCsv($request)
    {
        $afterDate = $request->input('after-date');
        $beforeDate = $request->input('before-date');

        if ($afterDate > $beforeDate) {
            return redirect()->back()->with('danger', "Некорректно введены даты");
        }

        $transactions = $this->userTransaction->where([
            ['updated_at', '>=', $afterDate],
            ['updated_at', '<=', $beforeDate],
            ['status_id', '1']
        ])->get();

        if ($transactions->isEmpty()) {
            return redirect()->back()->with('danger', "Нет данных для записи");
        }

        $pathToFile = storage_path() . "/app/" . 'result.csv';
        $handle = fopen($pathToFile, 'w');

        fputcsv($handle, [
            'Кто перевел',
            'Кому перевели',
            'Сумма',
            'Запланированное время транзакции',
        ]);

        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction['from_user_id'],
                $transaction['to_user_id'],
                $transaction['amount'],
                $transaction['dispatch_time'],
            ]);
        }

        fclose($handle);

        return response()->download($pathToFile);
    }
}
