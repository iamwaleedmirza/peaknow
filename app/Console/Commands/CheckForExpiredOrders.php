<?php

namespace App\Console\Commands;

use App\Http\Controllers\Email\EmailController;
use App\Http\Controllers\User\PaymentController;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckForExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckForExpiredOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for orders whose prescription is expired or refills are exhausted.';

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
        $orders = Order::where('status', 'Prescribed')
            ->where('is_active', true)
            ->where('is_exhausted', false)
            ->get();

        $currentDate = strtotime(date('Y-m-d'));

        foreach ($orders as $order) {
            if ($order->refill_count !== null && $order->refill_until_date !== null) {

                $isRefillExhausted = $order->refill_count >= 5;

                $isPrescriptionExpired = $currentDate > strtotime($order->refill_until_date);

                if ($isRefillExhausted || $isPrescriptionExpired) {
                    $order->update(['is_exhausted' => true]);

                    (new EmailController())->sendPlanExpiredMail($order);
                }
            }
        }
    }
}
