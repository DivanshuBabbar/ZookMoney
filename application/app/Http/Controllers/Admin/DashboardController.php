<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\User;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\Bulk_Files;
use App\Models\Payout;
use App\Models\MonthlyStats;
use App\Models\CurrencyExchangeRate;
use DB;
class DashboardController extends Controller
{
    public function new_dashboard()
    {
        $data['active'] = 'dashboard';
        $data['total_user'] = User::count();
        $exchangeRateINRtoUSDT = CurrencyExchangeRate::where('first_currency_id', '14')->where('second_currency_id', '13')->pluck('exchanges_to_second_currency_value')->first();
        $exchangeRateUSDTtoINR = 1 / $exchangeRateINRtoUSDT;
        $data['exchangeRateUSDTtoINR'] = number_format((float)$exchangeRateUSDTtoINR, 2, '.', '');
        return view('admin.dashboard', $data);
    }
    
    public function index(Request $request)
    {
      
    	return view('notus.dashboard.dashboard');
    }

    public function getCounts(){
        // User counts
        $userCounts = User::selectRaw("
            COUNT(*) AS total,
            SUM(account_status = 1) AS active,
            SUM(verified = 1) AS verified,
            SUM(account_status = 0) AS blocked,
            SUM(role_id = 1) AS total_admins,
            SUM(role_id = 1 AND verified = 1 AND account_status = 1) AS active_admins,
            SUM(role_id = 1 AND verified = 1) AS verified_admins,
            SUM(role_id = 1 AND account_status = 0) AS blocked_admins,
            SUM(is_merchant = 0) AS total_normal,
            SUM(is_merchant = 0 AND verified = 1 AND account_status = 1) AS active_normal,
            SUM(is_merchant = 0 AND verified = 1) AS verified_normal,
            SUM(is_merchant = 0 AND account_status = 0) AS blocked_normal,
            SUM(is_merchant = 1) AS total_merchant,
            SUM(is_merchant = 1 AND verified = 1 AND account_status = 1) AS active_merchant,
            SUM(is_merchant = 1 AND verified = 1) AS verified_merchant,
            SUM(is_merchant = 1 AND account_status = 0) AS blocked_merchant,
            SUM(role_id = 5) AS total_resellar,
            SUM(role_id = 5 AND verified = 1 AND account_status = 1) AS active_resellar,
            SUM(role_id = 5 AND verified = 1) AS verified_resellar,
            SUM(role_id = 5 AND account_status = 0) AS blocked_resellar,
            SUM(white_label_status = 1) AS white_label_status_zero,
            SUM(wire_transfer_status = 1) AS wire_transfer_status_zero,
            SUM(payout_status = 1) AS payout_status,
            SUM(payin_status = 1) AS payin_status,
            SUM(file_upload = 1) AS bulk_payout,
            SUM(status = 'spam') AS spam,
            SUM(status = 'inactive') AS inactive,
            SUM(status = 'suspicious') AS suspicious
        ")->first();
    
        // Merchant counts
        $merchantCounts = Merchant::selectRaw("
            SUM(status = 'In-principle Approval') AS merchant_approval_count,
            SUM(status = 'Approved') AS merchant_approval_counts,
            SUM(status = 'Rejected') AS merchant_rejected_counts,
            SUM(time_status = 0) AS merchant_t0_count,
            SUM(time_status = 24) AS merchant_t1_count,
            SUM(time_status = 48) AS merchant_t2_count,
            SUM(time_status = 72) AS merchant_t3_count
        ")->first();
    
        $counts = $userCounts;
        $counts->merchant_approval_count = $merchantCounts->merchant_approval_count;
        $counts->merchant_approval_counts = $merchantCounts->merchant_approval_counts;
        $counts->merchant_rejected_counts = $merchantCounts->merchant_rejected_counts;
        $counts->merchant_t0_count = $merchantCounts->merchant_t0_count;
        $counts->merchant_t1_count = $merchantCounts->merchant_t1_count;
        $counts->merchant_t2_count = $merchantCounts->merchant_t2_count;
        $counts->merchant_t3_count = $merchantCounts->merchant_t3_count;
    
        return response()->json($counts);
    }
    

    public function getTodayStats(){
        $todayStartUTC = now()->startOfDay()->setTimezone('UTC');
        $todayEndUTC = now()->endOfDay()->setTimezone('UTC');
    
        $transactions = Transaction::whereBetween('created_at', [$todayStartUTC, $todayEndUTC])
            ->where('transaction_state_id', 1)->get();
    
        $manualDepositCount = $transactions->where('activity_title', 'Manual Deposit From Dashboard')->sum('net');
        $purchaseSaleCount = $transactions->whereIn('activity_title', ['Manual Deposit'])->sum('net');
        $wire_count = $transactions->where('activity_title', 'Wire-Transfer')->sum('net');
    
        $payouts = Payout::whereBetween('created_at', [$todayStartUTC, $todayEndUTC])
        ->where('transaction_state_id', 1)
        ->selectRaw('SUM(CASE WHEN transactionable_type = "Bulk Transfer" THEN net ELSE 0 END) AS bulk_payouts')
        ->selectRaw('SUM(CASE WHEN transactionable_type = "Payout-Deposit" THEN net ELSE 0 END) AS payout_deposit')
        ->first();
    
        $bulk_payout = $payouts->bulk_payouts ?? 0;
        $payout_deposit = $payouts->payout_deposit ?? 0;
        $payout_amount = $bulk_payout + $payout_deposit;
    
    
        $withdrawals = $transactions->where('activity_title', 'Manual Withdraw')->sum('net');
        $transferred_to_payout = $transactions->where('activity_title', 'Payout A/C')->sum('net');
    
        $payin_incoming = $manualDepositCount + $purchaseSaleCount + $wire_count;
        $payin_outgoing = $bulk_payout + $payout_amount;
        $gross_incoming = $payin_incoming + $payout_deposit;
        $gross_outgoing = $withdrawals + $payin_outgoing;
    
        return response()->json([
            'manual_deposit_count' => $manualDepositCount,
            'purchase_sale_count' => $purchaseSaleCount,
            'wire_count' => $wire_count,
            'bulk_payouts' => $bulk_payout,
            'payout_amount' => $payout_amount,
            'payout_deposit' => $payout_deposit,
            'withdrawals' => $withdrawals,
            'transferred_to_payout' => $transferred_to_payout,
            'gross_incomings' => $gross_incoming,
            'gross_outgoing' => $gross_outgoing
        ]);
    }
    

    public function getTransactionStats(){
        $activities = [
            'upi' => ['Manual Deposit'],
            'wire' => ['Wire-Transfer'],
            'manual' => ['Manual Deposit From Dashboard'],
            'settlement' => ['Manual Withdraw'],
            'transferred' =>['Payout A/C'],
        ];
    
        $stats = [];
    
        foreach ($activities as $type => $activityTitles) {
            $activityStats = Transaction::whereIn('activity_title', $activityTitles)
                ->selectRaw('transaction_state_id, COUNT(*) as count')
                ->groupBy('transaction_state_id')
                ->get()
                ->keyBy('transaction_state_id')
                ->map->count
                ->toArray();
    
            $stats[$type.'_completed'] = $activityStats[1] ?? 0;
            $stats[$type.'_pending'] = $activityStats[3] ?? 0;
            $stats[$type.'_cancelled'] = $activityStats[2] ?? 0;
            $stats[$type.'_partially_completed'] = $activityStats[4] ?? 0;
        }
    
        $payoutTypes = ['Payout-Deposit', 'Transferred', 'Bulk Transfer'];
    
        foreach ($payoutTypes as $payoutType) {
            $payoutStats = Payout::where('transactionable_type', $payoutType)
                ->selectRaw('transaction_state_id, COUNT(*) as count')
                ->groupBy('transaction_state_id')
                ->get()
                ->keyBy('transaction_state_id')
                ->map->count
                ->toArray();
    
            $stats['payout_'.$payoutType.'_completed'] = $payoutStats[1] ?? 0;
            $stats['payout_'.$payoutType.'_pending'] = $payoutStats[3] ?? 0;
            $stats['payout_'.$payoutType.'_cancelled'] = $payoutStats[2] ?? 0;
            $stats['payout_'.$payoutType.'_partially_completed'] = $payoutStats[4] ?? 0;
        }
    
        $payoutStats = Payout::selectRaw('transaction_state_id, COUNT(*) as count')
            ->groupBy('transaction_state_id')
            ->get()
            ->keyBy('transaction_state_id')
            ->map->count
            ->toArray();
    
        $stats['payout_completed'] = $payoutStats[1] ?? 0;
        $stats['payout_pending'] = $payoutStats[3] ?? 0;
        $stats['payout_cancelled'] = $payoutStats[2] ?? 0;
        $stats['payout_partially_completed'] = $payoutStats[4] ?? 0;
    
        return response()->json($stats);
    }
    
    
    public function getOverallStats(){
        // Fetch transactions
        $transactions = Transaction::where('transaction_state_id', 1)->whereIn('activity_title', ['Manual Deposit From Dashboard', 'Manual Deposit', 'Wire-Transfer', 'Manual Withdraw', 'Payout A/C'])
            ->select('activity_title', DB::raw('SUM(net) as total_net'))
            ->groupBy('activity_title')
            ->get();
    
        $alltimeincoming = 0;
        $alltimesettlemet = 0;
    
        foreach ($transactions as $transaction) {
            if (in_array($transaction->activity_title, ['Manual Deposit From Dashboard', 'Manual Deposit', 'Wire-Transfer'])) {
                $alltimeincoming += $transaction->total_net;
            } else {
                $alltimesettlemet += $transaction->total_net;
            }
        }
    
        $payin_alltime = $alltimeincoming - $alltimesettlemet;
    
        $payouts = Payout::where('transaction_state_id', 1)
            ->whereIn('transactionable_type', ['Payout-Deposit', 'Transferred', 'Bulk Transfer'])
            ->select('transactionable_type', DB::raw('SUM(net) as total_net'))
            ->groupBy('transactionable_type')
            ->get();
    
        $incoming_payout = 0;
        foreach ($payouts as $payout) {
            if ($payout->transactionable_type === 'Payout-Deposit' || $payout->transactionable_type === 'Transferred') {
                $incoming_payout += $payout->total_net;
            }
        }
        $payout_bulkalltime = $payouts->where('transactionable_type', 'Bulk Transfer')->sum('total_net');
        $alltimepayout = $incoming_payout - $payout_bulkalltime;


        return response()->json(['payin_alltime' => $payin_alltime,'alltimepayout' => $alltimepayout]);
    }

    public function getOverallStatsRefresh(Request $request){
        $formattedTime = $request->formattedTime;
        $istDateTime = new \DateTime($formattedTime, new \DateTimeZone('Asia/Kolkata'));
        $istDateTime->setTimezone(new \DateTimeZone('UTC'));
        $utcTime = $istDateTime->format('Y-m-d H:i:s');
       
        $transactions = Transaction::where('transaction_state_id', 1)->where('created_at', '>=', $utcTime)
        ->whereIn('activity_title', ['Manual Deposit From Dashboard', 'Manual Deposit', 'Wire-Transfer', 'Manual Withdraw', 'Payout A/C'])
            ->select('activity_title', DB::raw('SUM(net) as total_net'))
            ->groupBy('activity_title')
            ->get();
    
        $alltimeincoming = 0;
        $alltimesettlemet = 0;
    
        foreach ($transactions as $transaction) {
            if (in_array($transaction->activity_title, ['Manual Deposit From Dashboard', 'Manual Deposit', 'Wire-Transfer'])) {
                $alltimeincoming += $transaction->total_net;
            } else {
                $alltimesettlemet += $transaction->total_net;
            }
        }
    
        $payin_alltime = $alltimeincoming - $alltimesettlemet;
        $payouts = Payout::where('transaction_state_id', 1)->where('created_at','>=',$utcTime)->whereIn('transactionable_type', ['Payout-Deposit', 'Transferred', 'Bulk Transfer'])
            ->select('transactionable_type', DB::raw('SUM(net) as total_net'))
            ->groupBy('transactionable_type')
            ->get();
    
        $incoming_payout = 0;
        foreach ($payouts as $payout) {
            if ($payout->transactionable_type === 'Payout-Deposit' || $payout->transactionable_type === 'Transferred') {
                $incoming_payout += $payout->total_net;
            }
        }
        $payout_bulkalltime = $payouts->where('transactionable_type', 'Bulk Transfer')->sum('total_net');
        $alltimepayout = $incoming_payout - $payout_bulkalltime;

        return response()->json(['payin_alltime' => $payin_alltime,'alltimepayout' => $alltimepayout]);
    }   

    public function getMonthlyStats(){
        $transactions = Transaction::where('transaction_state_id', 1)->whereIn('activity_title', ['Manual Deposit From Dashboard', 'Manual Deposit', 'Wire-Transfer', 'Manual Withdraw', 'Payout A/C'])
            ->whereMonth('created_at', '=', now()->month)
            ->select('user_id', 'activity_title', DB::raw('SUM(net) as total_net'))
            ->groupBy('user_id', 'activity_title')
            ->get();
       
            $transactionCounts = []; 
            foreach ($transactions as $transaction) {
                $transactionCounts[$transaction->user_id] = isset($transactionCounts[$transaction->user_id]) ? $transactionCounts[$transaction->user_id] + 1 : 1;
            }
            if ($transactionCounts) {
                $maxTransactionsUserId = array_keys($transactionCounts, max($transactionCounts))[0];
            }else{
                 $maxTransactionsUserId = '';
            }
            $payin_alltime = 0;
            $alltimesettlemet = 0;

            foreach($transactions as $transaction) {
                if (in_array($transaction->activity_title, ['Manual Deposit From Dashboard', 'Manual Deposit', 'Wire-Transfer'])) {
                    $payin_alltime += $transaction->total_net;
                } else {
                    $alltimesettlemet += $transaction->total_net;
                }
            }
        
        // Retrieve payouts
        $payouts = Payout::where('transaction_state_id', 1)->whereIn('transactionable_type', ['Payout-Deposit', 'Transferred', 'Bulk Transfer'])->whereMonth('created_at', '=', now()->month)
            ->select('transactionable_type', DB::raw('SUM(net) as total_net'))
            ->groupBy('transactionable_type')
            ->get();
    
        $incoming_payout = 0;
        foreach ($payouts as $payout) {
            if ($payout->transactionable_type === 'Payout-Deposit' || $payout->transactionable_type === 'Transferred') {
                $incoming_payout += $payout->total_net;
            }
        }
        $payout_bulkalltime = $payouts->where('transactionable_type', 'Bulk Transfer')->sum('total_net');
        $alltimepayout = $incoming_payout - $payout_bulkalltime;  
        $new_merchants = User::where('is_merchant', 1)->whereMonth('created_at', now()->month)->pluck('name');
        $merchant = $transactions->sortByDesc('total_net')->first();
        $user_id = $merchant->user_id ?? '';
        $userIds = [$user_id, $maxTransactionsUserId];
        $userNames = User::whereIn('id', $userIds)->pluck('name', 'id'); 
        $topMerchantName = $userNames[$user_id] ?? null;
        $activeMerchantName = $userNames[$maxTransactionsUserId] ?? null;
 
        $monthlyStatsData = [
            'montly_payin_alltime' => $payin_alltime,
            'monthly_alltimepayout' => $alltimepayout,
            'new_merchants' => $new_merchants,
            'top_merchant' => $topMerchantName,
            'active_merchant' => $activeMerchantName
        ];
    
        $monthlyStatsJson = json_encode($monthlyStatsData);
        $currentMonth = now()->format('Y-m');
        $currentMonthStats = MonthlyStats::where('month', $currentMonth)->first();
    
        if ($currentMonthStats) {
            $currentMonthStats->value = $monthlyStatsJson;
            $currentMonthStats->save();
        } else {
            MonthlyStats::create(['month' => $currentMonth,'value' => $monthlyStatsJson]);
        }
    
        return response()->json($monthlyStatsData);
    }
    
    //chargeback
    public function getChargebackStats(){
        $todayStartUTC = now()->startOfDay()->setTimezone('UTC'); 
        $todayEndUTC = now()->endOfDay()->setTimezone('UTC'); 
    
        $overall_changeback = Transaction::where('transaction_state_id', 1)->where('chargeback_status','1')->count();
        $today_changeback = Transaction::whereBetween('updated_at', [$todayStartUTC, $todayEndUTC])->where('transaction_state_id', 1)->where('chargeback_status','1')->count();
    
        $transactions = Transaction::where('transaction_state_id', 1)->where('chargeback_status', 1)->join('users', 'transactionable.user_id', '=', 'users.id')
        ->select('transactionable.user_id', 'users.name', 'users.email', DB::raw('count(*) as chargeback_count'))
        ->groupBy('transactionable.user_id', 'users.name', 'users.email')
        ->get();
    
        $user_chargeback_counts = $transactions->map(function ($transaction) {
            return ['user_id' => $transaction->user_id,'name' => $transaction->name,'email' => $transaction->email,'chargeback_count' => $transaction->chargeback_count,];
        })->toArray();
        
        return response()->json(['overall_changeback' => $overall_changeback,'today_changeback' => $today_changeback,'user_changeback_counts' => $user_chargeback_counts]);
    }

    //refresh chargeback
    public function getChargebackStatsRefresh(Request $request){

        $formattedTime = $request->formattedTime;
        $istDateTime = new \DateTime($formattedTime, new \DateTimeZone('Asia/Kolkata'));
        $istDateTime->setTimezone(new \DateTimeZone('UTC'));
        $utcTime = $istDateTime->format('Y-m-d H:i:s');
        
        $todayStartUTC = now()->startOfDay()->setTimezone('UTC');
        $todayEndUTC = now()->endOfDay()->setTimezone('UTC');
        
        $overall_changeback = Transaction::where('transaction_state_id', 1)->where('updated_at', '>=', $utcTime)->where('chargeback_status', '1')->count(); 
        $today_changeback = Transaction::whereBetween('updated_at', [$todayStartUTC, $todayEndUTC])->where('updated_at', '>=', $utcTime)->where('transaction_state_id', 1)->where('chargeback_status', '1')->count();

        $transactions = Transaction::where('transaction_state_id', 1)->where('chargeback_status', 1)->join('users', 'transactionable.user_id', '=', 'users.id')
        ->select('transactionable.user_id', 'users.name', 'users.email', DB::raw('count(*) as chargeback_count'))
        ->groupBy('transactionable.user_id', 'users.name', 'users.email')
        ->get();
    
        $user_chargeback_counts = $transactions->map(function ($transaction) {
        return ['user_id' => $transaction->user_id,'name' => $transaction->name,'email' => $transaction->email,'chargeback_count' => $transaction->chargeback_count,];
        })->toArray();
        
        return response()->json(['overall_changeback' => $overall_changeback,'today_changeback' => $today_changeback,'user_changeback_counts' => $user_chargeback_counts]);

    }   
}

