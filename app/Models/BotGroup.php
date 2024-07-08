<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table    = 'bot_groups';
    protected $fillable = [
        'question', 'answer', 'status', 'ordering',
    ];

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
 
    public function subscriber(): HasMany
    {
        return $this->hasMany(GroupSubscriber::class, 'group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'group_id');
    }


}
