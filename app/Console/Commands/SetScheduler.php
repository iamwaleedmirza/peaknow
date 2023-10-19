<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class SetScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:SetScheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting Different Scheduler for automating process';

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
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::where('payment_status', 'Unpaid')
            ->where('payment_page_success', false)
            ->where('status', 'Pending')
            ->get();

        foreach ($orders as $order) {

            $orderedDate = strtotime(date('Y-m-d H:i:s', strtotime($order->created_at))) + (60 * 20);
            $currentDate = strtotime(date('Y-m-d H:i:s'));
            if ($currentDate >= $orderedDate) {
                $order->is_active = false;
                $order->status = 'Cancelled';
                $order->telemedicine_consult = 0;
                $order->cancel_reason = "Order has been cancelled due to payment not received from the customer.";
                $order->save();
            }

        }
    }
}
