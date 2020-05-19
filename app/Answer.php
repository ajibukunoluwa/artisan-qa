<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'is_correct',
        'answer'
    ];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }
}
