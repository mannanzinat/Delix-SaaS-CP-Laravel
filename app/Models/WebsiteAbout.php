<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteAbout extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'title',
        'description',
        'status',
    ];

    protected $casts    = [
        'icon' => 'array',
    ];


    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteAboutLanguage::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsiteAboutLanguage::class, 'website_about_id', 'id')->where('lang', app()->getLocale());
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
