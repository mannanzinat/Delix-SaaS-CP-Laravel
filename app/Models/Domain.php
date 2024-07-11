<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'server_id',
        'sub_domain',
        'custom_domain',
        'custom_domain_active',
        'ssl_active',
        'dns_active',
        'script_deployed',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
