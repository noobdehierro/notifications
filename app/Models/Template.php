<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['channel_id', 'name', 'placeholder', 'template_name', 'url_image'];

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_template');
    }

    public function setPlaceholderAttribute($value)
    {
        $this->attributes['placeholder'] = $value ?? '';
    }
}
