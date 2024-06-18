<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'query_id', 'days', 'hour', 'is_active'];

    public function querydata()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'campaign_template');
    }
}
