<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTransaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Вывод формы для создания транзакции
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        $users = $user->all();

        if ($users->isNotEmpty()) {
            return view('home', ['users' => $users]);
        }

        throw new NotFoundHttpException();
    }

    public function getLastUsersTransactions(UserTransaction $userTransaction)
    {
        $lastTransactions = $userTransaction
            ->join('users as us1', 'users_transactions.from_user_id', 'us1.id')
            ->join('users as us2', 'users_transactions.to_user_id', 'us2.id')
            ->join('statuses', 'users_transactions.status_id', 'statuses.id')
            ->whereRaw('users_transactions.id =
                (select users_transactions.id from users_transactions WHERE users_transactions.from_user_id = us1.id 
                ORDER BY users_transactions.id DESC limit 1)')
            ->orderBy('id', 'DESC')
            ->select([
                'statuses.name as status_name', 'us1.first_name as fromUserName',
                'us2.first_name as toUserName', 'us1.balance', 'users_transactions.*'
            ])
            ->get();

        return $lastTransactions->isEmpty() ? redirect('/')->with('danger', 'Транзакции отсутствуют') :
            view('transactions', ['transactions' => $lastTransactions]);
    }
}
