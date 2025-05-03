<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // ✅ Add this relationship
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
