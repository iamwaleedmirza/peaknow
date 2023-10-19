<?php

namespace App\Models;

use App\Http\Controllers\Api\SendGridController;
use App\Models\Logs\AuthorizeCustomerLogs;
use App\Models\Logs\PaymentLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getDocumentPath($userId, $documentType)
    {
        return UserDocument::where('user_id', $userId)
            ->where('document_type', $documentType)
            ->select(['document_path'])
            ->first()['document_path'];
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function getLatestAddresses()
    {
        return $this->addresses()->orderBy('created_at', 'DESC');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function getOrderTotal($user_id)
    {
        return Order::where('user_id', $user_id)
            ->where('status', 'Prescribed')
            ->join('order_refills as or', 'orders.order_no', '=', 'or.order_no')
            ->where(function ($query) {
                $query->where('or.refill_status', '=', 'Confirmed')
                    ->orWhere('or.refill_status', '=', 'Completed');
            })
            ->select(DB::raw('SUM(or.amount) as order_total'))
            ->first()->order_total;
    }

    public function getOrderShippingTotal($user_id)
    {
        return 0;
        // return Order::where('user_id', $user_id)
        //     ->where('status', 'Prescribed')
        //     ->join('order_refills as or', 'orders.order_no', '=', 'or.order_no')
        //     ->where(function ($query) {
        //         $query->where('or.refill_status', '=', 'Confirmed')
        //             ->orWhere('or.refill_status', '=', 'Completed');
        //     })
        //     ->select(DB::raw('SUM(or.shipping_cost) as shipping_cost_total'))
        //     ->first()->shipping_cost_total;
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, 'id', 'user_id');
    }

    public function getResentVerificationByType($type)
    {
        return $this->logs()->where('type', $type)->orderBy('created_at', 'DESC')->first();
    }

    public function logs()
    {
        return $this->belongsTo(UserLogs::class, 'id', 'user_id');
    }

    public function getDocumnetByType($type)
    {
        return $this->documents()->where('document_type', $type)->orderBy('created_at', 'DESC')->first();
    }

    public function documents()
    {
        return $this->belongsTo(UserDocument::class, 'id', 'user_id');
    }

    public function libertyDetails()
    {
        return $this->hasOne(LibertyPatientDetails::class, 'user_id', 'id');
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
//        $data = ['email' => $this->email];

        $emailData = [
            'account_username' => $this->first_name . ' ' . $this->last_name,
            'year' => now()->format('Y'),
            'action_url' => route('password.reset', ['token' => $token, 'email' => $this->email])
        ];
        $mailResponse = SendGridController::sendMail($this->email, TEMPLATE_ID_FORGOT_PASSWORD, $emailData, 'account-update@peakscurative.com');

//        Mail::send('emails.forgot-password', [
//            'full_name' => $this->first_name . ' ' . $this->last_name,
//            'url' => route('password.reset', ['token' => $token, 'email' => $this->email]),
//        ],
//            function ($message) use ($data) {
//                $message->subject('Your password reset link');
//                $message->to($data['email']);
//            }
//        );
    }

    public function getActiveOrder()
    {
        return Order::where('user_id', $this->id)->where('is_active', true)->where('is_exhausted', false)->first();
    }

    public function customerPaymentLogs()
    {
        return $this->hasMany(PaymentLog::class, 'user_id', 'id');
    }

    public function authorizeCustomerLogs()
    {
        return $this->hasMany(AuthorizeCustomerLogs::class, 'user_id', 'id');
    }
}
