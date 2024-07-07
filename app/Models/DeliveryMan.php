<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Models\Account\CompanyAccount;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\DeliveryManAccount;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryMan extends Model
{
    use HasFactory;

    protected $with     = ['user', 'accountStatements', 'paymentLogs', 'companyAccount'];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function accountStatements(){
        return $this->hasMany(DeliveryManAccount::class)->latest();
    }

    public function paymentLogs(){
        return $this->hasMany(DeliveryManAccount::class);
    }

    public function companyAccount(){
        return $this->belongsTo(CompanyAccount::class, 'id', 'delivery_man_id')->where('source', 'opening_balance');
    }
    public function balance($id)
    {
        $total_income  = DeliveryManAccount::where('delivery_man_id', $id)->where('type','income')->sum('amount');
        $total_expense  = DeliveryManAccount::where('delivery_man_id', $id)->where('type','expense')->sum('amount');
        $balance = $total_income - $total_expense;

        return $balance;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Sentinel::check();
            $model->created_by = $user ? $user->id : null;
            $model->created_at = date('Y-m-d H:i:s');
        });

        static::updating(function ($model) {
            $user = Sentinel::check();
            $model->updated_by = $user ? $user->id : null;
            $model->updated_at = date('Y-m-d H:i:s');
        });
    }
}
