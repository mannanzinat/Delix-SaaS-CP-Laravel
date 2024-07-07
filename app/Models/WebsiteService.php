<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteService extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'description',
        'status',
    ];

    protected $casts    = [
        'image' => 'array',
    ];


    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteServiceLanguage::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsiteServiceLanguage::class, 'website_service_id', 'id')->where('lang', app()->getLocale());
    }


    public function getLangDescriptionAttribute()
    {
        return $this->language ? $this->language->description : $this->description;
    }

    public function getLangTitleAttribute()
    {
        return $this->language ? $this->language->title : $this->title;
    }
}
