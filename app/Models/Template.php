<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['channel_id', 'name', 'placeholder', 'path'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
