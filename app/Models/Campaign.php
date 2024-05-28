<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'templates_id'];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function channelsTemplates()
    {
        return $this->hasMany(CampaignChannelTemplate::class);
    }
}
