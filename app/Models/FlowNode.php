<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowNode extends Model
{
    use HasFactory;

    protected $fillable = ['flow_id', 'node_id', 'type', 'position', 'data'];

    protected $casts    = [
        'position' => 'array',
        'data'     => 'array',
    ];

    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class);
    }
}
