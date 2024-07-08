<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'name', 'data', 'status','contact_list_ids','segment_ids','flow_for','flow_type'];

    protected $casts = [
        'data' => 'array',
        'contact_list_ids' => 'array',
        'segment_ids' => 'array',
    ];

    public function nodes()
    {
        return $this->hasMany(FlowNode::class);
    }
    public function edges()
    {
        return $this->hasMany(FlowEdge::class);
    }
}
