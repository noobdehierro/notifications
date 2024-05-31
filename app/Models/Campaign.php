<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'query_id', 'days', 'hour', 'status'];

    public function queryRelation()
    {
        return $this->belongsTo(Query::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'campaign_template');
    }
}
