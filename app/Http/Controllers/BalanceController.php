<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Balance;
use Exception;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function getBalance(Request $request)
    {
        // $balance = Balance::with('account')->where('bussiness_id', $request->bussiness_id)
        //     ->orderBy(Account::select('number')->whereColumn('accounts.id', 'balances.account_id'))->get();
        $balance = Balance::select(['balances.*', 'accounts.number', 'accounts.name', 'accounts.account_nature_id'])
            ->join('accounts', 'balances.account_id', '=', 'accounts.id')
            ->orderBy('accounts.number')
            ->where('balances.bussiness_id', $request->bussiness_id)->get();
        // foreach ($balance as $accountBalance) {
        //     $accountBalance->account_number = $accountBalance->account->number;
        //     $accountBalance->account_nane = $accountBalance->account->name;
        //     $accountBalance->account_nature_id = $accountBalance->account->account_nature_id;
        //     unset($accountBalance->account);
        // }
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $balance
        ]);
    }

    public static function updatetBalance($bussiness_id, $detail)
    {
        $account = Account::where('bussiness_id', $bussiness_id)
            ->where('id', $detail['account_id'])->first();
        if (!$account) {
            throw new Exception('La cuenta ' . $detail['account'] . ' no existe');
        }
        $balanceAccount = Balance::where('bussiness_id', $bussiness_id)
            ->where('account_id', $detail['account_id'])->first();

        $debit = $detail['operationNature'] == 1 ? floatval($detail['amount']) : floatval(0);
        $credit = $detail['operationNature'] == 2 ? floatval($detail['amount']) : floatval(0);
        if ($balanceAccount) {
            $balanceAccount->debit += $debit;
            $balanceAccount->credit += $credit;
            $balanceAccount->amount = $account->account_nature_id == 1
                ? floatval($balanceAccount->start_amount + $balanceAccount->debit - $balanceAccount->credit)
                : floatval($balanceAccount->start_amount + $balanceAccount->credit - $balanceAccount->debit);
            $balanceAccount->update();
        } else {
            Balance::create([
                'account_id' => $detail['account_id'],
                'start_amount' => floatval(0),
                'debit' => $debit,
                'credit' => $credit,
                'amount' =>  $account->account_nature_id == 1
                    ? floatval($debit - $credit)
                    : floatval($credit - $debit),
                'bussiness_id' => $bussiness_id
            ]);
        }
    }
}
