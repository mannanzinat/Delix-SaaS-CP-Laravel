<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'plan_id',
        'is_recurring',
        'status',
        'purchase_date',
        'expire_date',
        'price',
        'package_type',
        'active_merchant',
        'monthly_parcel',
        'active_rider',
        'active_staff',
        'custom_domain',
        'branded_website',
        'white_level',
        'merchant_app',
        'rider_app',
        'trx_id',
        'payment_method',
        'payment_details',
        'canceled_at',
        'billing_name',
        'billing_email',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip_code',
        'billing_country',
        'billing_phone',
    ];

    protected $casts    = [
        'payment_details' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
