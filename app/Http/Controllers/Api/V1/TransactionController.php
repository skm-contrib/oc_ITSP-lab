<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\V1\TransactionResource;
use App\Http\Resources\V1\TransactionCollection;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use App\Models\Wallet;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('category')->get();
        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request)
    {
        $validatedData = $request->validated();
        $transactionType = $validatedData['transaction_type'];
        $amount = $validatedData['amount'];
        $walletId = $validatedData['wallet_id'];

        $wallet = Wallet::findOrFail($walletId);

        if ($transactionType === 'expense' && $wallet->balance < $amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        $transaction = Transaction::create($validatedData);

        if ($validatedData['transaction_type'] === 'income') {
            $wallet->addBalance($validatedData['amount']);
        } elseif ($validatedData['transaction_type'] === 'expense') {
            $wallet->subtractBalance($validatedData['amount']);
        }

    return response()->json('Transaction created successfully');
    }

    public function update(StoreTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return response()->json('Transaction updated successfully');
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->json('Transaction deleted successfully');
    }

    public function getTransactionsByWallet($wallet_id)
    {
        // return new TransactionCollection(Transaction::where('wallet_id', $wallet_id)->get());
        $transactionsByDate = Transaction::with('category')->where('wallet_id', $wallet_id)
            ->orderBy('transaction_date')
            ->get()
            ->groupBy(function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('Y-m-d');
            })
            ->map(function ($transactions, $date) {
                return [
                    'date' => $date,
                    'transactions' => $transactions,
                ];
            })
            ->values()
            ->toArray();
        return response()->json($transactionsByDate);
    }

    public function getIncome(Request $request, $walletId)
    {
        $period = $request->query('period');
        switch ($period) {
            case 'day':
                $startDate = Carbon::now()->toDateString();
                $endDate = Carbon::now()->toDateString();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek()->toDateString();
                $endDate = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'half_year':
                $startDate = Carbon::now()->startOfYear()->toDateString();
                $endDate = Carbon::now()->startOfYear()->addMonths(6)->toDateString();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear()->toDateString();
                $endDate = Carbon::now()->endOfYear()->toDateString();
                break;
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }


        $totalIncome = Transaction::where('wallet_id', $walletId)
            ->where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');


        return response()->json(
            ['total_income' => $totalIncome,
            'start_date' => Carbon::parse($startDate)->format('Y.m.d'),
            'end_date' => Carbon::parse($endDate)->format('Y.m.d')]);
    }

    public function getExpense(Request $request, $walletId)
    {
        $period = $request->query('period');
        switch ($period) {
            case 'day':
                $startDate = Carbon::now()->toDateString();
                $endDate = Carbon::now()->toDateString();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek()->toDateString();
                $endDate = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'half_year':
                $startDate = Carbon::now()->startOfYear()->toDateString();
                $endDate = Carbon::now()->startOfYear()->addMonths(6)->toDateString();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear()->toDateString();
                $endDate = Carbon::now()->endOfYear()->toDateString();
                break;
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

        $totalExpense = Transaction::where('wallet_id', $walletId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

            return response()->json(
            ['total_expense' => $totalExpense,
            'start_date' => Carbon::parse($startDate)->format('Y.m.d'),
            'end_date' => Carbon::parse($endDate)->format('Y.m.d')]);
    }

    public function searchTransactions(Request $request, $walletId)
    {
        $search = $request->query('search');
        $transactions = Transaction::where('wallet_id', $walletId)
        ->where(function ($query) use ($search) {
            $query->where('description', 'like', "%$search%")
            ->orWhere('amount', 'like', "%$search%");
        })->get();

        return new TransactionCollection($transactions);
    }
}
