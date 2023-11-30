<?php


namespace App\Http\Services;


use App\Http\Resources\WalletResource;
use JetBrains\PhpStorm\ArrayShape;

class WalletService
{
    public function __construct()
    {
        $this->ensureWalletExists();
    }

    #[ArrayShape(['wallet' => "\App\Http\Resources\WalletResource"])] public function getInfo(): array
    {
        $wallet = currentUser()->wallet;
        return [
            'wallet' => WalletResource::make($wallet)
        ];
    }

    /**
     * @param $data
     */
    public function deposit($data)
    {
        currentUser()->transactions()->deposit($data['amount']);

    }

    /**
     * @param $data
     */
    public function withdraw($data)
    {
        currentUser()->transactions()->withdraw($data['amount']);
    }

    protected function ensureWalletExists()
    {
        if (!exists(currentUser()->wallet)) {
            currentUser()->wallet()->create();
        }
    }
}
