<?php

namespace App\Models;

use App\Models\Logs\PaymentLog;
use App\Models\Logs\SubscriptionLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //To Get User Data for order
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function subscription()
    {
        return $this->hasOne(OrderSubscription::class, 'order_no', 'order_no');
    }
    public function getSubscribedPlan()
    {
        return $this->subscription()
//            ->where('active_status', '1')
            ->orderBy('updated_at', 'DESC')->first();
    }
    //To Get Transaction Data for subscribed order
    public function getTransaction()
    {
        return $this->transaction()
            ->where('transaction_type', 'subscription_payment')
            ->orderBy('updated_at', 'DESC')->get();
    }
    public function getLatestTransaction()
    {
        return $this->transaction()
            ->where('transaction_type', 'subscription_payment')->orderBy('created_at','DESC')->first();
    }
    public function getTotalTransactionCount()
    {
        return $this->transaction()
            ->where('transaction_type', 'subscription_payment')->get()->count();
    }
    public function transaction()
    {
        return $this->hasMany(OrderRefill::class, 'order_no', 'order_no');
    }
    public function orderRefundHistory()
    {
        return $this->hasMany(OrderRefundHistory::class, 'order_no', 'order_no');
    }
    public function getRefundTransaction()
    {
        return $this->orderRefundHistory()->orderBy('updated_at','DESC')->first();
    }
    public function subscriptionRefill()
    {
        return $this->hasOne(OrderRefill::class, 'order_no', 'order_no');
    }
    public function orderRefill()
    {
        return $this->hasOne(OrderRefill::class, 'order_no', 'order_no');
    }

    public function getOrderRefills()
    {
        return $this->hasMany(OrderRefill::class, 'order_no', 'order_no')
            ->orderBy('created_at','DESC');
    }

    public function subscriptionLogs()
    {
        return $this->hasMany(SubscriptionLogs::class, 'order_no', 'order_no');
    }

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class, 'order_no', 'order_no');
    }

    public static function getOrderId($orderNo)
    {
        return (Order::where('order_no', $orderNo)->first())->id;
    }

    public function belugaOrder() {
       return $this->hasOne(BelugaOrderDetail::class,'order_no','order_no');
    }

    public function orderQuestionAnswer() {
       return $this->hasOne(QuestionAnswer::class,'order_no','order_no');
    }

    public function order_question_answer() {
       return $this->hasOne(QuestionAnswer::class,'id','question_ans_id');
    }

    public function newRefillConfirmed()
    {
        return $this->hasOne(OrderRefill::class, 'order_no', 'order_no')->where('refill_number',0)->whereIn('refill_status',['Confirmed','Completed'])->count();
    }
}
