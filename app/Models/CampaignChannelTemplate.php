<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignChannelTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['campaign_id', 'channel_id', 'template_id', 'send'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
