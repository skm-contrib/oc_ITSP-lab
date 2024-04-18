<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Resources\V1\WalletResource;
use App\Http\Resources\V1\WalletCollection;
use App\Models\Wallet;


class WalletController extends Controller
{
    public function index()
    {
        return WalletResource::collection(Wallet::all());
    }

    public function store(StoreWalletRequest $request)
    {
        Wallet::create($request->validated());
        return response()->json('Wallet created successfully');
    }

    public function update(StoreWalletRequest $request, Wallet $wallet)
    {
        $wallet->update($request->validated());
        return response()->json('Wallet updated successfully');
    }

    public function show(Wallet $wallet)
    {
        return new WalletResource($wallet);
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        return response()->json('Wallet deleted successfully');
    }
    public function getWalletsByUserId($user_id)
    {
        return new WalletCollection(Wallet::where('user_id', $user_id)->get());
    }
}
