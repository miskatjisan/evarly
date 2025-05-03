<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title', 'duration'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}

