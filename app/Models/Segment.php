<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'client_id'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
 
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : null;
            $model->created_at = date('Y-m-d H:i:s');
        });

        static::updating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : null;
            $model->updated_at = date('Y-m-d H:i:s');
        });
    }

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
}