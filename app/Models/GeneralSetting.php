<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    public $timestamps = false;
    protected $fillable = [
        'site_title',
        'site_logo_light',
        'site_logo_dark',
        'site_favicon',
        'footer_text',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'support_mail',
        'privacy_policy_page',
        'terms_condition_page',
        'cookie_policy_page',
        'telehealth_consent_page',
        'refund_policy_page',
        'allowed_states',
        'shipping_cost',
        'consultation_fee'
    ];
}
