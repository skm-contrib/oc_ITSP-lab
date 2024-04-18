<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletBalanceHistory extends Model
{
    use HasFactory;

    protected $table = 'wallet_balance_history';


    protected $fillable = ['wallet_id', 'balance', 'balance_date'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
