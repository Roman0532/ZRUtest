<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvWriteRequest;
use App\Http\Requests\TransactionRequest;
use App\Services\CsvTransactionsService;
use App\User;
use App\UserTransaction;

class TransactionController extends Controller
{
    /**
     * Метод сохранения транзакции в бд
     * @param TransactionRequest $request
     * @param UserTransaction $userTransaction
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TransactionRequest $request, UserTransaction $userTransaction, User $user)
    {
        $transferAmount = $request->input('amount');
        $fromUser = $request->input('fromUser');
        $toUser = $request->input('toUser');
        $dateScheduledTransaction = $request->input('date');

        if ($transferAmount * 100 % 50 !== 0) {
            return redirect()->back()->with('danger', 'Сумма должна быть кратной 0.50 копеек');
        }

        $amountWriteOffsWaitingTransactions = $userTransaction->where([
            ['from_user_id', $fromUser],
            ['status_id', 3]
        ])->sum('amount');

        $userBalance = $user->find($fromUser)->balance - $amountWriteOffsWaitingTransactions;

        if ($userBalance < $transferAmount) {
            return redirect()->back()->with('danger', 'Недостаточно средств для перевода');
        }

        $userTransaction->create([
            'from_user_id' => $fromUser,
            'to_user_id' => $toUser,
            'dispatch_time' => $dateScheduledTransaction,
            'amount' => $transferAmount,
            'status_id' => 3
        ]);

        return redirect()->back()->with('success', "Запись была успешно добавлена.
         Запланированное время транзации {$dateScheduledTransaction}");
    }

    /**
     * Метод записи транзакций в csv
     * @param CsvWriteRequest $request
     * @param CsvTransactionsService $csvTransactionsService
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getTransactionsInCsv(CsvWriteRequest $request, CsvTransactionsService $csvTransactionsService)
    {
        return $csvTransactionsService->writeApprovedTransactionsInCsv($request);
    }
}
