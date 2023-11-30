<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
//use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class TransactionHappened extends Notification
{
    use Queueable;

    protected Transaction|Model|Builder $transaction;
    protected bool $accepted;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction, bool $accepted)
    {
        $this->transaction = $transaction;
        $this->accepted = $accepted;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    #[ArrayShape(['tx_id' => "mixed", 'title' => "mixed", 'message' => "mixed"])]
    public function toDatabase(): array
    {
        return [
            'tx_id' => $this->transaction->getAttribute('id'),
            'title' => $this->accepted ? trans('notif.SUCCESSFUL_TX_TITLE') : trans('notif.FAIL_TX_TITLE'),
            'message' => $this->accepted ? trans('notif.VERIFIED_TX') : trans('notif.FAILED_TX')
        ];
    }

}
