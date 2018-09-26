<?php

namespace App\Http\Controllers;

use App\UserTransaction;

class ApiTransactionController extends Controller
{
    public function getLastTransaction(UserTransaction $transaction)
    {
        $lastTransaction = $transaction->all()->last();

        return $lastTransaction->isNotEmpty() ?
            response()->json($lastTransaction, 200) : response()->json([], 204);

    }
}
