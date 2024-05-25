<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['campaign_id', 'sent_at', 'status'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
