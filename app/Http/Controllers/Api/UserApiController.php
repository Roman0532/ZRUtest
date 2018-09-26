<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTransaction;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        return $user->all()->isEmpty() ? response()->json($user->all()) : response()->json([], 204);
    }

    public function getLastUsersTransaction(UserTransaction $userTransaction)
    {
        $lastTransactions = $userTransaction->join('users as us1', 'users_transactions.from_user_id', 'us1.id')
            ->join('users as us2', 'users_transactions.to_user_id', 'us2.id')->join('statuses', 'users_transactions.status_id', 'statuses.id')
            ->whereRaw('users_transactions.id =
                (select users_transactions.id from users_transactions WHERE users_transactions.from_user_id = us1.id 
                ORDER BY users_transactions.id DESC limit 1)')
            ->orderBy('id', 'DESC')
            ->select(['statuses.name as status_name', 'us1.first_name as fromUserName', 'us2.first_name as toUserName', 'us1.balance', 'users_transactions.*'])
            ->get();

        return $lastTransactions->isEmpty() ? response()->json([], 204) : response()->json($lastTransactions);
    }
}
