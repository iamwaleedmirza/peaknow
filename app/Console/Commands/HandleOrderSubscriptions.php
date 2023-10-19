<?php

namespace App\Console\Commands;

use App\Http\Controllers\Email\EmailController;
use App\Http\Controllers\User\OrderRefillController;
use App\Http\Controllers\Utils\OrderUtilsController;
use App\Models\Logs\SubscriptionLogs;
use App\Models\Order;
use App\Services\AuthorizeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HandleOrderSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:HandleOrderSubscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle payments for Order Subscriptions & request for refill on Liberty API.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('is_active', true)
            ->where('is_subscription', true)
            ->where('is_exhausted', false)
            ->where('status', 'Prescribed')
            ->get();

        $orderUtils = new OrderUtilsController();

        foreach ($orders as $order) {

            if (!$orderUtils->isRefillsAvailable($order)) {
                continue;
            }

            $subscription = $order->subscription;
            $user = $order->user;

            if ($subscription->is_paused) {
                continue;
            }

            $nextRefillDate = Carbon::parse($subscription->next_refill_date)->toDateTimeString();
            $currentDate = now()->toDateTimeString();

            if ($currentDate >= $nextRefillDate) {
                $transactionResponse = (new AuthorizeService())->makeTransaction($user->customer_profile_id, $user->payment_profile_id, $order, true);

                if ($transactionResponse['success']) {
                    $orderRefillController = new OrderRefillController();
                    $orderRefillController->refillOrder($order, $transactionResponse['transaction_id']);
                } else {
                    $subscription->update([
                        'is_paused' => true,
                        'updated_by' => 'SYSTEM'
                    ]);
                    SubscriptionLogs::store($order->order_no, 'PAUSED', 'SYSTEM');

                    $emailManager = new EmailController();
                    $emailManager->sendSubscriptionRefillPaymentFailedMail($order);
                }
            }
        }
    }
}
