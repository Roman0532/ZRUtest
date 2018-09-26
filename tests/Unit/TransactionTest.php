<?php

namespace Tests\Feature;

use App\Services\TransactionsService;
use App\User;
use App\UserTransaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use withoutMiddleware;

    private $client;
    private $user;
    private $userTransaction;
    private $transactionService;

    public function setUp()
    {
        parent::setUp();

        $this->client = new Client();
        $this->userTransaction = new UserTransaction();
        $this->user = new User();
        $this->transactionService = new TransactionsService($this->userTransaction);
    }

    /**
     * Тест на создание транзакции
     *
     * @return void
     */
    public function testCreateTransaction()
    {
        $nowDate = Carbon::now()->addHour();

        $nowDate = $nowDate->format('Y-m-d H:00:00');

        $this->json('POST', '/transaction', [
            'fromUser' => 1,
            'toUser' => 2,
            'amount' => 0.5,
            'date' => $nowDate
        ]);

        $this->assertDatabaseHas('users_transactions', ['from_user_id' => 1, 'to_user_id' => 2, 'amount' => 0.5, 'dispatch_time' => $nowDate]);
        $this->userTransaction->where([['from_user_id', 1], ['to_user_id', 2], ['amount', 0.5], ['dispatch_time', $nowDate]])->delete();
    }

    /**
     * Тест на работоспособность транзакции
     *
     * @return void
     */
    public function testBeginTransaction()
    {
        DB::beginTransaction();

        $nowDate = Carbon::now();

        $nowDate = $nowDate->format('Y-m-d H:00:00');

        $this->userTransaction->create(['from_user_id' => 1, 'to_user_id' => 2, 'dispatch_time' => $nowDate, 'amount' => 0.5, 'status_id' => 3]);

        $testUserBeforeTransaction = $this->user->find(1);
        $testToUserBeforeTransaction = $this->user->find(2);

        $this->transactionService->startingTransactions();

        $testUserAfterTransaction = $this->user->find(1);
        $testToUserAfterTransaction = $this->user->find(2);

        $this->assertTrue($testUserBeforeTransaction->balance - 0.50 == $testUserAfterTransaction->balance);
        $this->assertTrue($testToUserBeforeTransaction->balance + 0.50 == $testToUserAfterTransaction->balance);

        $testUserAfterTransaction->update(['balance' => $testUserAfterTransaction->balance + 0.50]);
        $testToUserAfterTransaction->update(['balance' => $testToUserAfterTransaction->balance - 0.50]);

        DB::rollBack();
    }

    /**
     * Тест на нехватку средств для перевода
     *
     * @return void
     */
    public function testInsufficientFunds()
    {
        $nowDate = Carbon::now()->addHour();

        $nowDate = $nowDate->format('Y-m-d H:00:00');

        $response = $this->json('POST', '/transaction', [
            'fromUser' => 1,
            'toUser' => 2,
            'amount' => 500000,
            'date' => $nowDate
        ]);

        $response->assertSessionHas('danger');
        $this->assertEquals("Недостаточно средств для перевода", session()->get('danger'));
    }

    /**
     * Тест на кратность 50 копейкам
     *
     * @return void
     */
    public function testMultiplicity()
    {
        $nowDate = Carbon::now()->addHour();

        $nowDate = $nowDate->format('Y-m-d H:00:00');

        $response = $this->json('POST', '/transaction', [
            'fromUser' => 1,
            'toUser' => 2,
            'amount' => 0.51,
            'date' => $nowDate
        ]);

        $response->assertSessionHas('danger');
        $this->assertEquals("Сумма должна быть кратной 0.50 копеек", session()->get('danger'));
    }
}
