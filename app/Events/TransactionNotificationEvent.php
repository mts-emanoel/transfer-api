<?php

namespace App\Events;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class TransactionNotificationEvent extends Event
{
    use SerializesModels;

    /**
     *
     * @var User
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user_receiver, Transaction $transaction)
    {
        $this->user = $user_receiver;
        $this->transaction = $transaction;
    }
}
