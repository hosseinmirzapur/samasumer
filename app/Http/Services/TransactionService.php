<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\TxResource;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class TransactionService
{
    #[ArrayShape([
        'txs' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"
    ])]
    public function userTxs(): array
    {
        $transactions = currentUser()->transactions()->get();
        return [
            'txs' => TxResource::collection($transactions)
        ];
    }

    #[ArrayShape([
        'txs' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"
    ])]
    public function search($q): array
    {
        if (!exists($q)) {
            return $this->userTxs();
        }
        $transactions = currentUser()
            ->transactions()
            ->where('code', 'LIKE', '%' . $q . '%')
            ->orWhere('gateway', 'LIKE', '%' . $q . '%')
            ->orWhere('payment_type', 'LIKE', '%' . $q . '%')
            ->orWhere('amount', 'LIKE', '%' . $q . '%')
            ->orWhere('description', 'LIKE', '%' . $q . '%')
            ->get();
        return [
            'txs' => TxResource::collection($transactions)
        ];
    }

    /**
     * @throws Throwable
     */
    public function checkTx($data)
    {
        $status = $data['status'];
        $txId = $data['txId'];
        $tx = Transaction::query()->where('tx_id', $txId)->first();
        throw_if(!exists($tx), new CustomException('TX_NOT_FOUND'));
        if (!$status) {
            $tx->reject();
        }
        try {
            // ...
            $tx->verify();
        } catch (Exception $e) {
            $tx->reject();
            Log::error($e->getMessage());
        }
    }
}
