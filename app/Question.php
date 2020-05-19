<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'correct_answer',
        'attempts',
        'title'
    ];

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function getStatusAttribute($value)
    {
        if ($value == true) {
            return "Passed";
        } else {
            return (is_null($value)) ? "Not attempted" : "Failed";
        }
    }

    public function scopeIdle($query)
    {
        return $query->where('attempts', '=', 0);
    }
}
