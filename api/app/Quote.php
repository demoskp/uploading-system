<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'phone', 'more_info', 'part_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function part()
    {
        return $this->belongsTo('App\Part');
    }
}
