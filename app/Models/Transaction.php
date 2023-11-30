<?php

namespace App\Models;

use App\Notifications\TransactionHappened;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    const STATUS = ['FAILED', 'PENDING', 'SUCCESSFUL'];
    const TYPES = ['DEPOSIT', 'WITHDRAW', 'ONLINE_PAYMENT'];

    public function verify()
    {
        $this->update([
            'status' => 'SUCCESSFUL'
        ]);
        currentUser()->notify(new TransactionHappened($this, true));
    }

    public function reject()
    {
        $this->update([
            'status' => 'FAILED'
        ]);
        currentUser()->notify(new TransactionHappened($this, false));
    }

    /**
     * @param $amount
     * @return $this
     */
    public function deposit($amount): static
    {
        $this->fill([
            'amount' => $amount,
            'payment_type' => 'DEPOSIT'
        ]);
        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function withdraw($amount): static
    {
        $this->fill([
            'amount' => $amount,
            'payment_type' => 'WITHDRAW'
        ]);
        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function onlinePayment($amount): static
    {
        $this->fill([
            'amount' => $amount,
            'payment_type' => 'ONLINE_PAYMENT'
        ]);
        return $this;
    }
}
