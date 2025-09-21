<?php

namespace App\Services\Payments;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function credit(User $user, float $amount, array $meta = []): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $meta) {
            $user->increment('wallet_balance', $amount);

            return $user->transactions()->create([
                'amount' => $amount,
                'type' => Transaction::TYPE_CREDIT,
                'status' => Transaction::STATUS_COMPLETED,
                'reference' => $meta['reference'] ?? Str::uuid()->toString(),
                'meta' => $meta,
            ]);
        });
    }

    public function debit(User $user, float $amount, array $meta = []): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $meta) {
            if ($user->wallet_balance < $amount) {
                throw new \RuntimeException('Insufficient balance');
            }

            $user->decrement('wallet_balance', $amount);

            return $user->transactions()->create([
                'amount' => $amount,
                'type' => Transaction::TYPE_DEBIT,
                'status' => Transaction::STATUS_COMPLETED,
                'reference' => $meta['reference'] ?? Str::uuid()->toString(),
                'meta' => $meta,
            ]);
        });
    }
}
