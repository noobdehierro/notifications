<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientCopy extends Model
{
    use HasFactory;

    protected $table = 'recipients_copy';

    protected $fillable = [
        'campaign_id',
        'email',
        'msisdn',
        'created_at',
        'updated_at',
    ];


}
