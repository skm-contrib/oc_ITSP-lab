<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'wallet_name', 'currency', 'balance', 'color'];


    public function addBalance($amount)
    {
        $this->balance += $amount;
        $this->save();

        $this->recordBalanceHistory($amount);
    }

    public function subtractBalance($amount)
    {
        $this->balance -= $amount;
        $this->save();

        $this->recordBalanceHistory(-$amount);
    }

    protected function recordBalanceHistory($amount)
    {
        $this->balanceHistory()->create([
            'wallet_id' => $this->id,
            'balance' => $this->balance,
            'balance_change' => $amount,
            'balance_date' => now(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function balanceHistory()
    {
        return $this->hasMany(WalletBalanceHistory::class);
    }

}
